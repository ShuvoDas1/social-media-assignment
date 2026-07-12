<?php

namespace App\Http\Controllers\Api\V1\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostRequest;
use App\Http\Resources\PostCommentResource;
use App\Http\Resources\PostReactionResource;
use App\Http\Resources\PostResource;
use App\Services\PostService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct(protected PostService $postService)
    {
    }

    public function index(Request $request)
    {
        try {
            $posts = $this->postService->getAll($request->all());
            return $this->paginateResponse('Posts fetched successfully', 200, PostResource::collection($posts));
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), $th->getCode());
        }
    }

    public function store(PostRequest $request)
    {
        try {
            $postData = $this->postService->store($request->validated());
            return $this->successResponse('Post created successfully', 200, $postData);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), $th->getCode());
        }
    }

    public function update(PostRequest $request, $id)
    {
        try {
            $postData = $this->postService->update($request->validated(), $id);
            return $this->successResponse('Post updated successfully', 200, $postData);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), $th->getCode());
        }
    }

    public function delete($id)
    {
        try {
            $postData = $this->postService->delete($id);
            return $this->successResponse('Post deleted successfully', 200, $postData);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), $th->getCode());
        }
    }

    public function show($id)
    {
        try {
            $postData = $this->postService->show($id);
            return $this->successResponse('Post fetched successfully', 200, PostResource::collection($postData));
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), $th->getCode());
        }
    }

    public function postReaction(int $postId, Request $request)
    {
        try {
            $validated = $request->validate([
                'react' => 'required|in:like,unlike',
            ]);
            $postData = $this->postService->postReaction($validated, $postId);
            return $this->successResponse('Post reaction updated successfully', 200, $postData);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), $th->getCode());
        }
    }

    public function postComment(Request $request, int $postId)
    {

        try {
            $validated = $request->validate([
                'comment' => 'required',
                'parent_id' => 'nullable|exists:post_comments,id',
            ]);

            $postData = $this->postService->addComment($validated, $postId);
            return $this->successResponse('Post comment added successfully', 200, $postData);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), $th->getCode());
        }
    }

    public function commentReaction(int $commentId, Request $request)
    {
        try {
            $validated = $request->validate([
                'react' => 'required|in:like,unlike',
            ]);
            $postData = $this->postService->addCommentReaction($validated, $commentId);
            return $this->successResponse('Comment reaction added successfully', 200, $postData);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), $th->getCode());
        }
    }

    public function postComments(int $postId, Request $request)
    {
        try {
            $comments = $this->postService->getPostComments($postId, $request->all());
            return $this->paginateResponse('Comments fetched successfully', 200, PostCommentResource::collection($comments));
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), $th->getCode());
        }
    }

    public function postReactions(int $postId, Request $request)
    {
        try {
            $reactions = $this->postService->getPostReactions($postId, $request->all());
            return $this->paginateResponse('Post reaction fetched successfully', 200, PostReactionResource::collection($reactions));
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), $th->getCode());
        }
    }
}
