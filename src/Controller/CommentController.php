<?php

namespace App\Controller;

use App\Dto\Request\CommentRequest;
use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostRepository;
use App\Security\PostVoter;
use App\Service\CommentService;
use App\Service\RequestService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/comment", name="comment_")
 */
class CommentController extends AbstractController
{
    /**
     * Add comment.
     *
     * @Route("", name="new", methods={"POST"})
     *
     * @OA\Tag(name="Comment")
     *
     * @OA\RequestBody(required=true, @Model(type=CommentRequest::class))
     *
     * @OA\Response(response=201, description="Comment created")
     */
    public function new(
        Request $request,
        RequestService $requestService,
        CommentService $commentService,
        PostRepository $postRepository,
    ): JsonResponse {
        /* @var CommentRequest $commentDto */
        $commentDto = $requestService->hydrate($request->getContent(), CommentRequest::class);
        if (!$commentDto instanceof CommentRequest) {
            return new JsonResponse(null, Response::HTTP_BAD_REQUEST);
        }
        /** @var Post $post */
        $post = $postRepository->find($commentDto->postId);
        $this->denyAccessUnlessGranted(PostVoter::ADD_COMMENT, $post);

        /** @var User $user */
        $user = $this->getUser();
        $commentService->createAndPersistFromDto($commentDto, $user, $post);

        return new JsonResponse(null, Response::HTTP_CREATED);
    }
}
