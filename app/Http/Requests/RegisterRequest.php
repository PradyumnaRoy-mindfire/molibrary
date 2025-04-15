<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            //
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->where(function ($query)  {
                    return $query->where('role', $this->role);
                })
            ],
            'phone' => 'required|max:10|min:10',
            'password' => 'required|min:6',
            'role' => 'required|in:librarian,member',
            'library_id' => 'required_if:role,librarian|exists:libraries,id',
        ];
    }
}
