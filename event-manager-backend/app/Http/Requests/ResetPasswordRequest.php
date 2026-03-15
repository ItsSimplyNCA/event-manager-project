<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest {

    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ];
    }

    public function messages(): array {
        return [
            'token.required' => 'Reset token is missing.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Email address is invalid.',
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Passwords do not match.',
            'password.min' => 'Password must be az least 8 characters.',
        ];
    }
}
