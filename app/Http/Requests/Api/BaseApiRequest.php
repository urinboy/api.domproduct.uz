<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

/**
 * Base API Request class with standardized error handling
 */
abstract class BaseApiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        throw new HttpResponseException(
            response()->json([
                'error' => 'Validation Error',
                'message' => 'The given data was invalid.',
                'errors' => $errors,
                'meta' => [
                    'timestamp' => now()->toISOString(),
                    'path' => $this->path(),
                    'method' => $this->method(),
                ]
            ], 422)
        );
    }

    /**
     * Custom error messages for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            'required' => 'The :attribute field is required.',
            'string' => 'The :attribute must be a string.',
            'integer' => 'The :attribute must be an integer.',
            'boolean' => 'The :attribute must be true or false.',
            'numeric' => 'The :attribute must be a number.',
            'min' => 'The :attribute must be at least :min.',
            'max' => 'The :attribute may not be greater than :max.',
            'in' => 'The selected :attribute is invalid.',
            'exists' => 'The selected :attribute does not exist.',
            'email' => 'The :attribute must be a valid email address.',
            'unique' => 'The :attribute has already been taken.',
        ];
    }

    /**
     * Custom attribute names for validation
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'per_page' => 'items per page',
            'page' => 'page number',
            'sort_by' => 'sort field',
            'sort_order' => 'sort direction',
            'min_price' => 'minimum price',
            'max_price' => 'maximum price',
            'category_id' => 'category',
            'is_featured' => 'featured status',
        ];
    }
}
