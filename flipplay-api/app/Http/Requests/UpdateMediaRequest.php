<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $media = $this->route('media');

        return [
            'code' => ['sometimes', 'required', 'string', 'max:50', Rule::unique('media', 'code')->ignore($media)],
            'title' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('media', 'title')->ignore($media)],
            'type' => ['sometimes', 'required', 'in:anime,manga'],
            'genre' => ['sometimes', 'required', 'string', 'max:255'],
            'rating' => ['sometimes', 'required', 'numeric', 'min:0', 'max:10'],
            'synopsis' => ['sometimes', 'required', 'string'],
            'is_available' => ['sometimes', 'boolean'],
            'total_episodes' => ['nullable', 'integer', 'min:1'],
            'studio' => ['nullable', 'string', 'max:255'],
            'resolution' => ['nullable', 'string', 'max:100'],
            'has_sub_indo' => ['nullable', 'boolean'],
            'total_chapters' => ['nullable', 'integer', 'min:1'],
            'author' => ['nullable', 'string', 'max:255'],
            'is_colored' => ['nullable', 'boolean'],
            'is_completed' => ['nullable', 'boolean'],
        ];
    }
}
