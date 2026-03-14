<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique('posts', 'title')->ignore($this->route('id')),
            ],
            'description' => ['required', 'string', 'min:10'],
            'creator' => ['required', 'exists:users,id'],
            'image' => [
                $this->isMethod('post') ? 'required' : 'nullable',
                'file',
                'image',
                'extensions:jpg,png',
            ],
        ];
    }
}
