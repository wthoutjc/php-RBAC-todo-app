<?php

namespace App\Http\Requests;

class UpdateTaskRequest extends ApiFormRequest
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
            'title' => 'string|max:255',
            'description' => 'string',
            'slug' => "string|unique:tasks,slug,{$this->route('task')}",
            'status' => 'in:pending,in progress,completed',
            'user_id' => 'exists:users,id',
        ];
    }
}
