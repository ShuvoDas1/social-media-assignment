<?php

namespace App\Services;

use App\Models\Post;


class PostService
{
    public function __construct(protected FileUploadService $uploadService)
    {

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
}
