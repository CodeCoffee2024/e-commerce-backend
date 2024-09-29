<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'name' => ['nullable'],
            'email' => [
                'nullable',
                'email',
                'unique:users,email',
                'required_if:isGoogleAccount,false,isFacebookAccount,false'
            ],
            'mobileNumber' => ['nullable'],
            'email_verified_at' => ['nullable'],
            'password' => [
                'nullable',
                'string',
                'min:8',
                'required_if:isGoogleAccount,false,isFacebookAccount,false',
                'regex:/[a-z]/', // At least one lowercase letter
                'regex:/[A-Z]/', // At least one uppercase letter
                'regex:/[!@#$%^&*(),.?":{}|<>]/', // At least one special character
            ],
            'repeatPassword' => [
                'nullable',
                'string',
                'required_if:isGoogleAccount,false,isFacebookAccount,false',
                'same:password',
            ],
            'isGoogleAccount' => 'required', 
            'isFacebookAccount' => 'required',
            'google.displayName' => 'nullable|string',
            'google.photoURL' => 'nullable|string',
            'google.email' => 'nullable|email',
            'google.phoneNumber' => 'nullable|string',
            'google.uid' => 'nullable|string',
        ];
    }
    public function messages()
    {
        return [
            'email.required_if' => 'The email is required',
            'email.unique' => 'Email has already taken',
            'password.required_if' => 'The password is required',
            'repeatPassword.required_if' => 'The repeat password is required',
            'repeatPassword.same' => 'The repeat password must match the password.',
            'password.regex' => 'The password must contain at least one lowercase letter, one uppercase letter, and one special character.'
        ];
    }
}
