<?php

namespace App\Http\Requests;

use App\Rules\ImageValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'min:3',
                Rule::unique('posts')->ignore($this->route('post'))
            ],
            'description' => 'required|min:10',
            'user_id' => 'sometimes|exists:users,id',
            'image' => [
                'nullable',
                'file',
                'max:2048', // 2MB max
                new ImageValidationRule(),
            ],
        ];
    }
}
