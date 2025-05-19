<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AssignAdminRequest extends FormRequest
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
                })
            ],
            'phone' => [
                'required', 
                'size:10',
                'regex:/^\d{10}$/'
            ],
            'password' => [
                'required',
                'min:6',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]+$/'
            ],
        ];
    }
    public function messages()
    {
        return [
            'password.regex' => 'Password must include at least one uppercase letter, one lowercase letter, one digit, and one special character.',
            'name.regex' => 'Digits are not allowed in the name.',
            'email.regex' => 'Please enter a valid email address.',
            'phone.regex' => 'Phone number must contain only digits.',
        ];
    }
}
