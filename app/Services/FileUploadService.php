<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class FileUploadService
{
    protected string $disk;

    public function __construct(string $disk = 'public')
    {
        $this->disk = $disk;
    }


    public function upload(UploadedFile $file, string $folder = 'uploads'): string
    {
        $fileName = $this->generateFileName($file);
        $path = $file->storeAs($folder, $fileName, $this->disk);

        return $path;
    }


    public function uploadMultiple(array $files, string $folder = 'uploads'): array
    {
        $paths = [];
        foreach ($files as $file) {
            $paths[] = $this->upload($file, $folder);
        }
        return $paths;
    }


    public function generateFileName(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        return Str::uuid() . '_' . time() . '.' . $extension;
    }


    public function delete(string $path): bool
    {
        if (Storage::disk($this->disk)->exists($path)) {
            return Storage::disk($this->disk)->delete($path);
        }
        return false;
    }


    public function deleteMultiple(array $paths): void
    {
        foreach ($paths as $path) {
            $this->delete($path);
        }
    }


    public function exists(string $path): bool
    {
        return Storage::disk($this->disk)->exists($path);
    }

    public function url(string $path): string
    {
        return Storage::disk($this->disk)->url($path);
    }

    public function validateFile(UploadedFile $file, array $allowedTypes = ['jpg', 'jpeg', 'png', 'pdf'], int $maxSizeMB = 5): bool
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $sizeInMB = $file->getSize() / 1024 / 1024;

        if (!in_array($extension, $allowedTypes)) {
            return false;
        }

        if ($sizeInMB > $maxSizeMB) {
            return false;
        }

        return true;
    }

}