<?php

namespace App\Security;

use App\Entity\Post;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class PostVoter extends Voter
{
    public const ADD_COMMENT = 'add_comment';

    public function __construct(private readonly Security $security)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (self::ADD_COMMENT != $attribute) {
            return false;
        }

        if (!$subject instanceof Post) {
            return false;
        }

        return true;
    }

    /**
     * @param Post $subject
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        return match ($attribute) {
            self::ADD_COMMENT => $this->canAddComment(),
            default => throw new \LogicException('This code should not be reached!')
        };
    }

    private function canAddComment(): bool
    {
        return $this->security->isGranted('ROLE_USER');
    }
}
