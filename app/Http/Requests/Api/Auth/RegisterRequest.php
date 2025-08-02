<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\BaseApiRequest;

/**
 * User registration request validation
 */
class RegisterRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'phone' => 'required|string|regex:/^[+]?[0-9]{10,15}$/|unique:users,phone',
            'password' => 'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
            'password_confirmation' => 'required|same:password',
            'language' => 'sometimes|string|in:uz,ru,en',
            'terms_accepted' => 'required|boolean|accepted',
        ];
    }

    /**
     * Custom validation messages
     *
     * @return array
     */
    public function messages()
    {
        return array_merge(parent::messages(), [
            'name.required' => 'Name field is required.',
            'name.min' => 'Name must be at least 2 characters.',
            'email.required' => 'Email field is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email is already registered.',
            'phone.required' => 'Phone number is required.',
            'phone.regex' => 'Please provide a valid phone number.',
            'phone.unique' => 'This phone number is already registered.',
            'password.required' => 'Password field is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number and one special character.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password_confirmation.required' => 'Password confirmation is required.',
            'password_confirmation.same' => 'Password confirmation must match the password.',
            'terms_accepted.required' => 'You must accept the terms and conditions.',
            'terms_accepted.accepted' => 'You must accept the terms and conditions.',
            'language.in' => 'Language must be one of: uz, ru, en.',
        ]);
    }

    /**
     * Prepare the data for validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // Clean phone number
        if ($this->has('phone')) {
            $phone = preg_replace('/[^+0-9]/', '', $this->phone);
            $this->merge(['phone' => $phone]);
        }

        // Clean email
        if ($this->has('email')) {
            $this->merge(['email' => strtolower(trim($this->email))]);
        }

        // Convert terms_accepted to boolean
        if ($this->has('terms_accepted')) {
            $this->merge([
                'terms_accepted' => filter_var($this->terms_accepted, FILTER_VALIDATE_BOOLEAN)
            ]);
        }
    }

    /**
     * Custom attribute names
     *
     * @return array
     */
    public function attributes()
    {
        return array_merge(parent::attributes(), [
            'name' => 'full name',
            'email' => 'email address',
            'phone' => 'phone number',
            'password' => 'password',
            'password_confirmation' => 'password confirmation',
            'terms_accepted' => 'terms and conditions',
        ]);
    }
}
