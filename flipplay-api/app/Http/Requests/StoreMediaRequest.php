<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:50', 'unique:media,code'],
            'title' => ['required', 'string', 'max:255', 'unique:media,title'],
            'type' => ['required', 'in:anime,manga'],
            'genre' => ['required', 'string', 'max:255'],
            'rating' => ['required', 'numeric', 'min:0', 'max:10'],
            'synopsis' => ['required', 'string'],
            'is_available' => ['sometimes', 'boolean'],
            'total_episodes' => ['nullable', 'required_if:type,anime', 'integer', 'min:1'],
            'studio' => ['nullable', 'required_if:type,anime', 'string', 'max:255'],
            'resolution' => ['nullable', 'string', 'max:100'],
            'has_sub_indo' => ['nullable', 'boolean'],
            'total_chapters' => ['nullable', 'required_if:type,manga', 'integer', 'min:1'],
            'author' => ['nullable', 'required_if:type,manga', 'string', 'max:255'],
            'is_colored' => ['nullable', 'boolean'],
            'is_completed' => ['nullable', 'boolean'],
        ];
    }
}
