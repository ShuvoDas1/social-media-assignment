<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    public function attributes()
    {
        return [
            'fname' => 'First Name',
            'lname' => 'Last Name',
            'email' => 'Email address',
            'password' => 'Password',
            'password_confirmation' => 'Password Confirmation',
        ];
    }

    public function messages()
    {
        return [
            'email.unique' => 'This email address is already registered',
            'password.confirmed' => 'Password confirmation does not match',
        ];
    }

    public function response(array $errors) {
        if ($this->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'code' => 422,
                'errors' => $errors,
            ], 422);
        }
    }
}
