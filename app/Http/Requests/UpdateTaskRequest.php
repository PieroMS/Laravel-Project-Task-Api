<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
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
            'title' => 'required|between:3,100',
            'description' => 'nullable',
            'status' => 'required|in:pending,progress,done',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'El campo es requerido.',
            'title.between' => 'El nombre debe tener entre 3 y 100 caracteres.',

            'description.required' => 'El campo es requerido.',

            'status.required' => 'El campo es requerido.',
            'status.in' => 'El estado es invalido, debe ser pending, progress o done.',

            'priority.required' => 'El campo es requerido.',
            'priority.in' => 'La prioridad es invalida, debe ser low, medium o high.',

            'due_date.required' => 'El campo es requerido.',
        ];
    }
}
