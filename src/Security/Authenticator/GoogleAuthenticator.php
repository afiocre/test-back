<?php

namespace App\Security\Authenticator;

use App\Entity\User;
use App\Repository\UserRepository;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use League\OAuth2\Client\Provider\GoogleUser;
use League\OAuth2\Client\Token\AccessToken;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class GoogleAuthenticator extends OAuth2Authenticator
{
    private ClientRegistry $clientRegistry;
    private UserRepository $userRepository;

    public function __construct(
        ClientRegistry $clientRegistry,
        UserRepository $userRepository,
    ) {
        $this->clientRegistry = $clientRegistry;
        $this->userRepository = $userRepository;
    }

    public function supports(Request $request): ?bool
    {
        return 'user_login_google' === $request->attributes->get('_route');
    }

    public function authenticate(Request $request): Passport
    {
        $accessTokenArray = json_decode((string) $request->getContent(), true);
        if (!isset($accessTokenArray['id_token'])) {
            throw new CustomUserMessageAuthenticationException('No Google token provided');
        }

        $accessToken = new AccessToken($accessTokenArray);
        /** @var GoogleUser $googleUser */
        $googleUser = $this->clientRegistry->getClient('google_main')->fetchUserFromToken($accessToken);
        if (null === $googleUser->getEmail()) {
            throw new CustomUserMessageAuthenticationException('No email from Google account');
        }

        // Find User by Google id
        $user = $this->userRepository->findOneBy(['googleId' => $googleUser->getId()]);
        if (null === $user) {
            // Find User by Google email
            $user = $this->userRepository->findOneBy(['email' => $googleUser->getEmail()]);
            if ($user) {
                $user->setGoogleId($googleUser->getId());
                $this->userRepository->flush();
            } else {
                // Create User
                $user = new User();
                $user->setEmail($googleUser->getEmail())
                    ->setFirstname($googleUser->getFirstName() ?: 'inconnu')
                    ->setGoogleId($googleUser->getId());
                $this->userRepository->add($user);
            }
        }

        return new SelfValidatingPassport(new UserBadge($user->getEmail()));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData()),
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
}
