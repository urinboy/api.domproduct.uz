<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\BaseApiRequest;

/**
 * User login request validation
 */
class LoginRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required_without:phone|email|exists:users,email',
            'phone' => 'required_without:email|string|exists:users,phone',
            'password' => 'required|string|min:8',
            'remember_me' => 'sometimes|boolean',
            'device_name' => 'sometimes|string|max:255',
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
            'email.required_without' => 'Email or phone number is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.exists' => 'No account found with this email address.',
            'phone.required_without' => 'Phone number or email is required.',
            'phone.exists' => 'No account found with this phone number.',
            'password.required' => 'Password field is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'device_name.max' => 'Device name is too long.',
        ]);
    }

    /**
     * Prepare the data for validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // Clean email
        if ($this->has('email') && $this->email) {
            $this->merge(['email' => strtolower(trim($this->email))]);
        }

        // Clean phone number
        if ($this->has('phone') && $this->phone) {
            $phone = preg_replace('/[^+0-9]/', '', $this->phone);
            $this->merge(['phone' => $phone]);
        }

        // Convert remember_me to boolean
        if ($this->has('remember_me')) {
            $this->merge([
                'remember_me' => filter_var($this->remember_me, FILTER_VALIDATE_BOOLEAN)
            ]);
        }

        // Set default device name
        if (!$this->has('device_name') || !$this->device_name) {
            $userAgent = $this->header('User-Agent', 'Unknown Device');
            $deviceName = $this->extractDeviceName($userAgent);
            $this->merge(['device_name' => $deviceName]);
        }
    }

    /**
     * Extract device name from User-Agent
     *
     * @param string $userAgent
     * @return string
     */
    private function extractDeviceName(string $userAgent): string
    {
        // Simple device detection
        if (preg_match('/Mobile|Android|iPhone|iPad/', $userAgent)) {
            if (preg_match('/iPhone/', $userAgent)) {
                return 'iPhone';
            } elseif (preg_match('/iPad/', $userAgent)) {
                return 'iPad';
            } elseif (preg_match('/Android/', $userAgent)) {
                return 'Android Device';
            } else {
                return 'Mobile Device';
            }
        }

        if (preg_match('/Chrome/', $userAgent)) {
            return 'Chrome Browser';
        } elseif (preg_match('/Firefox/', $userAgent)) {
            return 'Firefox Browser';
        } elseif (preg_match('/Safari/', $userAgent)) {
            return 'Safari Browser';
        }

        return 'Web Browser';
    }

    /**
     * Custom attribute names
     *
     * @return array
     */
    public function attributes()
    {
        return array_merge(parent::attributes(), [
            'email' => 'email address',
            'phone' => 'phone number',
            'password' => 'password',
            'remember_me' => 'remember me option',
            'device_name' => 'device name',
        ]);
    }
}
