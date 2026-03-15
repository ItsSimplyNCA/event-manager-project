<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest {

    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'title' => ['required', 'string', 'max:255'],
            'occurs_at' => ['required', 'date'],
            'description' => ['nullable', 'string'],
        ];
    }

    public function messages(): array {
        return [
            'title.required' => 'Title is required.',
            'title.max' => 'Title can be up to 255 characters.',
            'occurs_at.required' => 'Date is required.',
            'occurs_at.date' => 'Date is invalid.',
        ];
    }
}
