<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'is_active' => 'boolean',
        ];
    }


    public function messages()
    {
        return [
            'name.required' => "O campo nome é obrigatório.",
            'name.max' => "O campo nome deve ter no máximo 255 caracteres.",
            'is_active.boolean' => "Tipo de dado não permitido",
        ];
    }
}
