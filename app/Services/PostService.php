<?php

namespace App\Services;

use App\Models\Post;
use App\Models\PostComment;
use App\Models\PostReaction;
use Exception;


class PostService
{
    public function __construct(protected FileUploadService $uploadService)
    {

    }

    public function getAll(array $filters)
    {
        $query = Post::with(['images', 'comments', 'reactions']);

        $limit = $filters["limit"] ?? 50;
        $page = $filters["page"] ?? 1;


        if (isset($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (isset($filters['visibility'])) {
            $query->where('visibility', $filters['visibility']);
        }

        $query->visibleTo()->reactionsCount()->orderByCreated();


        return $query->paginate($limit);
    }

    public function getOne($id)
    {
        return Post::find($id);

    }

    public function store(array $data)
    {
        $post = Post::create([
            'user_id' => auth()->id(),
            'content' => $data['content'],
            'visibility' => $data['visibility'],
        ]);

        if (isset($data['images'])) {
            $images = $this->uploadService->uploadMultiple($data['images'], 'posts');
            foreach ($images as $image) {
                $post->images()->create([
                    'image_path' => $image,
                ]);
            }
        }

        return $post;
    }

    public function update(array $data, $id)
    {
        $post = Post::where('user_id', auth()->id())->findOrFail($id);
        if (isset($data['images'])) {
            $images = $this->uploadService->uploadMultiple($data['images'], 'posts');
            foreach ($images as $image) {
                $post->images()->create([
                    'image_path' => $image,
                ]);
            }
        }
        $post->update($data);
        return $post;
    }

    public function delete($id)
    {
        $post = Post::where('user_id', auth()->id())->findOrFail($id);
        $post->delete();
        return $post;
    }

    public function show($id)
    {
        $post = Post::with(['images', 'reactions'])->findOrFail($id);
        return $post;
    }

    public function postReaction(array $data, $postId)
    {

        $post = $this->getOne($postId);

        if (!$post)
            throw new Exception("Post was not found!", 404);

        if ($post->user_id !== auth()->id() && $post->isPrivate())
            throw new Exception("You can't react to this post!", 403);

        if ($data['react'] === 'unlike') {
            $response = $post->reactions()->where('user_id', auth()->id())->delete();
        } else {
            $response = $post->reactions()->create([
                'user_id' => auth()->id(),
                'react' => $data['react'],
            ]);
        }
        return $response;
    }

    public function addComment(array $data, int $postId)
    {
        $post = $this->getOne($postId);
        if (!$post)
            throw new Exception("Post was not found!", 404);

        if ($post->user_id !== auth()->id() && $post->isPrivate())
            throw new Exception("You can't comment on this post!", 403);

        $comment = $post->comments()->create([
            'user_id' => auth()->id(),
            'comment' => $data['comment'],
            'parent_id' => $data['parent_id'] ?? null,
        ]);
        return $comment;
    }

    public function addCommentReaction(array $data, int $commentId)
    {
        $comment = PostComment::findOrFail($commentId);
        $reaction = $comment->reactions()->updateOrCreate(
            [
                'comment_id' => $commentId,
                'user_id' => auth()->id(),
            ],
            [
                'react' => $data['react'],
            ]
        );
        return $reaction;
    }

    public function getPostComments(int $postId, array $filters)
    {
        $query = PostComment::where('post_id', $postId)->topLevel()->reactionsCount()->latest()->with('user', 'reactions', 'replies');
        $limit = $filters['limit'] ?? 10;
        return $query->paginate($limit);
    }

    public function getPostReactions(int $postId, array $filters = [])
    {
        $query = PostReaction::where('post_id', $postId)->with('user')->latest();
        $limit = $filters['limit'] ?? 10;
        return $query->paginate($limit);
    }
}
