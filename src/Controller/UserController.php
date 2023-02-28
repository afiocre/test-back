<?php

namespace App\Controller;

use App\Dto\Response\UserResponse;
use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user", name="user_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/login/google", name="login_google", methods={"POST"})
     *
     * @OA\Tag(name="User")
     *
     * @OA\RequestBody(required=true, description="Google AccessToken", @OA\JsonContent(
     *
     *     @OA\Property(property="scope", type="string"),
     *     @OA\Property(property="token_type", type="string"),
     *     @OA\Property(property="id_token", type="string"),
     *     @OA\Property(property="access_token", type="string"),
     *     @OA\Property(property="expires", type="integer"),
     * ))
     *
     * @OA\Response(response=200, description="Login successfull", @OA\JsonContent(
     *
     *     @OA\Property(property="token", type="string"),
     *     @OA\Property(property="user", ref=@Model(type=UserResponse::class)),
     * ))
     *
     * @OA\Response(response=401, description="Unauthorized")
     */
    public function loginGoogle(JWTTokenManagerInterface $jwtManager): JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $User = new UserResponse(
            $user->getId(),
            $user->getFirstname(),
            $user->getEmail(),
            $user->getRoles()
        );

        return new JsonResponse([
            'token' => $jwtManager->create($user),
            'user' => $User,
        ]);
    }

    /**
     * @Route("/logout", name="logout", methods={"GET"})
     *
     * @OA\Tag(name="User")
     */
    public function logout(): void
    {
    }
}
