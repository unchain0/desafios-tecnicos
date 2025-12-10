<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public const MAX_TITLE_LENGTH = 255;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:'.self::MAX_TITLE_LENGTH,
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'O título é obrigatório.',
            'title.max' => 'O título não pode ter mais de '.self::MAX_TITLE_LENGTH.' caracteres.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'title' => $this->sanitizeTitle(),
        ]);
    }

    private function sanitizeTitle(): string
    {
        return strip_tags(trim($this->input('title', '')));
    }
}
