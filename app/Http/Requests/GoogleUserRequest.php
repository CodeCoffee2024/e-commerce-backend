<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GoogleUserRequest extends FormRequest
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
            'email' => ['nullable','email'],
            'mobileNumber' => ['nullable'],
            'email_verified_at' => ['nullable'],
            'password' => 'nullable|string|min:8', // Ensure a minimum length for security
            'isGoogleAccount' => 'required', 
            'isFacebookAccount' => 'required',
            'google.displayName' => 'string',
            'google.photoURL' => 'string',
            'google.email' => 'email',
            'google.phoneNumber' => 'nullable|string',
            'google.uid' => 'string',
        ];
    }
}
