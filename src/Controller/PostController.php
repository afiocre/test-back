<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/post", name="post_")
 */
class PostController extends AbstractController
{
    /**
     * Get posts.
     *
     * @Route("", name="list", methods={"GET"})
     *
     * @OA\Tag(name="Post")
     *
     * @OA\Response(response=200, description="Post list", @OA\JsonContent(
     *     type="array",
     *
     *     @OA\Items(ref=@Model(type=Post::class))
     * ))
     */
    public function list(
        PostRepository $postRepository,
        SerializerInterface $serializer
    ): Response {
        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups('show')
            ->toArray();

        return new Response($serializer->serialize(
            $postRepository->findAll(),
            JsonEncoder::FORMAT,
            $context
        ));
    }

    /**
     * Get post.
     *
     * @Route("/{id}", name="details", methods={"GET"})
     *
     * @OA\Tag(name="Post")
     *
     * @OA\Response(response=200, description="Post details", @Model(type=Post::class))
     */
    public function details(
        Post $post,
        SerializerInterface $serializer
    ): Response {
        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups('show')
            ->toArray();

        return new Response($serializer->serialize(
            $post,
            JsonEncoder::FORMAT,
            $context
        ));
    }
}
