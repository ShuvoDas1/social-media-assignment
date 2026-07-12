<?php

namespace App\Services;

use App\Models\Post;


class PostService
{
    public function __construct(protected FileUploadService $uploadService)
    {

    }

    public function getAll(array $filters)
    {
        $query = Post::with(['images',]);

        $limit = $filters["limit"] ?? 10;
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
        $post = Post::findOrFail($postId);
        $post->reactions()->create([
            'user_id' => auth()->id(),
            'react' => $data['react'],
        ]);
        return $post;
    }
}
