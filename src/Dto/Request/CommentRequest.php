<?php

namespace App\Dto\Request;

use OpenApi\Annotations as OA;
use Symfony\Component\Validator\Constraints as Assert;

class CommentRequest
{
    /**
     * @Assert\NotBlank
     *
     * @OA\Property(type="string")
     */
    public mixed $content;

    /**
     * @Assert\NotBlank
     *
     * @OA\Property(type="integer")
     */
    public mixed $postId;
}
