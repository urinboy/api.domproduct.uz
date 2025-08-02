<?php

namespace App\Http\Requests\Api;

/**
 * Product show request validation
 */
class ProductShowRequest extends BaseApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'language' => 'sometimes|string|in:uz,ru,en',
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
            'language.in' => 'Language must be one of: uz, ru, en.',
        ]);
    }
}
