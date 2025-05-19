<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;

use Illuminate\Foundation\Http\FormRequest;

class EditAdminRequest extends FormRequest
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
        $user = $this->route('admin');
        return [
            //
            'name' => [
                'required',
                 'string', 
                 'max:255', 
                 'regex:/^[^\d]*$/'
                ],
            'email' => [
                'required',
                'email',
                'regex:/^[\w\.-]+@[\w\.-]+\.\w{2,4}$/',
                Rule::unique('users')->where(function ($query) {
                    return $query->where('role', 'library_admin');
                })->ignore($user->id)
            ],
            'phone' => [
                'required', 
                'size:10',
                'regex:/^\d{10}$/'
            ],
        ];
    }
    public function messages()
    {
        return [
            'name.regex' => 'Digits are not allowed in the name.',
            'email.regex' => 'Please enter a valid email address.',
            'phone.regex' => 'Phone number must contain only digits.',
        ];
    }
}
