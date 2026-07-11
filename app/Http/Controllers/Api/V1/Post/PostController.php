<?php

namespace App\Http\Controllers\Api\V1\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostRequest;
use App\Services\PostService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct(protected PostService $postService)
    {
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
}
