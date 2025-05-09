<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
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
            'name' => 'required|unique:projects|between:3,100',
            'description' => 'nullable',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El campo es requerido.',
            'name.unique' => 'El nombre debe ser unico',
            'name.between' => 'El nombre debe tener entre 3 y 100 caracteres.',

            'status.required' => 'El campo es requerido.',
            'status.in' => 'El estado es invalido, debe ser activo o inactivo.',
        ];
    }
}
