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
            // 'title' => 'string|max:255',
            // 'slug' => "string|unique:tasks,slug,{$this->route('task')}",
            // 'description' => 'string',
            // 'status' => 'in:pending,in progress,completed',
            // 'files.*' => 'file|mimes:jpg,jpeg,png,pdf,xlsx|max:3072',
        ];
    }
}
