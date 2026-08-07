<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmailTemplateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('email_template')?->id;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('email_templates', 'name')->ignore($id),
            ],
            'description' => ['nullable', 'string'],
            'file_html' => ['nullable', 'file', 'mimetypes:text/html', 'max:2048'],
        ];
    }
}
