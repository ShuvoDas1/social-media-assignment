<?php

namespace App\Http\Requests\Post;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "content" => "required|string",
            "visibility" => "required|in:public,private",
            "images" => "nullable|array|max:5",
            "images.*" => "image|mimes:jpg,jpeg,png,webp|max:2048",
        ];
    }

    public function messages(): array
    {
        return [
            "content.required" => "Content is required",
            "content.string" => "Content must be a string",
            "visibility.required" => "Visibility is required",
            "visibility.in" => "Visibility must be public or private",
            "images.array" => "Images must be an array",
            "images.max" => "Images must be at most 5",
            "images.*.image" => "Image must be an image",
            "images.*.mimes" => "Image must be a file of type: jpg,jpeg,png,webp",
            "images.*.max" => "Image must be at most 2048kb",
        ];
    }

    public function attributes(): array
    {
        return [
            "content" => "Content",
            "visibility" => "Visibility",
            "images" => "Images",
            "images.*" => "Image",
        ];
    }
}
