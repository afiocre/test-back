<?php

namespace App\Service;

use App\Dto\Request\CommentRequest;
use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use App\Repository\CommentRepository;

class CommentService
{
    public function __construct(
        private readonly CommentRepository $commentRepository,
    ) {
    }

    public function createAndPersistFromDto(CommentRequest $dto, User $user, Post $post): void
    {
        $comment = new Comment();
        $comment->setContent($dto->content)
            ->setCreatedBy($user)
            ->setPost($post);
        $this->commentRepository->save($comment, true);
    }
}
