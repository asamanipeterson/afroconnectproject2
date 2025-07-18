<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
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
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
            'gender' => 'required|in:male,female,other,prefer_not_to_say',
            'language' => 'required|string|max:50',
            'location' => 'required|string|max:50',
            'phone' => 'required|digits_between:1,10', // or 'numeric' if you want to allow other number formats
            'dob' => 'required|date|before:today',
        ];
    }
    public function messages(): array
    {
        return [
            'username.required' => 'Username is required.',
            'email.required' => 'Email is required.',
            'password.required' => 'Password is required.',
            'password_confirmation.required' => 'Password confirmation is required.',
            'gender.required' => 'Gender is required.',
            'language.required' => 'Language is required.',
            'location.required' => 'Location is required.',
            'phone.required' => 'Enter a valid phone number.',
            'dob.required' => 'Date of birth is required.',
        ];
    }
}
