<?php

namespace App\Http\Requests;

class StoreTaskRequest extends ApiFormRequest
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
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:tasks,slug',
            'description' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:pending,in progress,completed',
        ];
    }
}
