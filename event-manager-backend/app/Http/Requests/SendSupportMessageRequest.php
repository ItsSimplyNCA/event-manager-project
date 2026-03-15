<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendSupportMessageRequest extends FormRequest {

    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'message' => ['required', 'string', 'max:5000'],
        ];
    }

    public function messages(): array {
        return [
            'message.required' => 'Message is required.',
            'message.max' => 'Message can be up to 5000 characters.',
        ];
    }

}
