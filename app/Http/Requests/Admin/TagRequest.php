<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TagRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('tags', 'name')->ignore($this->route('tag'), 'tag_id'),
            ],
            'slug' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('tags', 'slug')->ignore($this->route('tag'), 'tag_id'),
            ],
        ];
    }
}
