<?php

namespace App\Http\Requests\Api;

/**
 * Product list request validation
 */
class ProductIndexRequest extends BaseApiRequest
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
            'category_id' => 'sometimes|integer|exists:categories,id',
            'is_featured' => 'sometimes|boolean',
            'search' => 'sometimes|string|max:255|min:2',
            'min_price' => 'sometimes|numeric|min:0',
            'max_price' => 'sometimes|numeric|min:0|gte:min_price',
            'sort_by' => 'sometimes|string|in:name,price,created_at,updated_at',
            'sort_order' => 'sometimes|string|in:asc,desc',
            'page' => 'sometimes|integer|min:1',
            'per_page' => 'sometimes|integer|min:1|max:50',
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
            'search.min' => 'Search term must be at least 2 characters.',
            'max_price.gte' => 'Maximum price must be greater than or equal to minimum price.',
            'per_page.max' => 'You cannot request more than 50 items per page.',
            'language.in' => 'Language must be one of: uz, ru, en.',
            'sort_by.in' => 'Sort field must be one of: name, price, created_at, updated_at.',
            'sort_order.in' => 'Sort order must be either asc or desc.',
        ]);
    }

    /**
     * Prepare the data for validation
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // Convert string boolean to actual boolean
        if ($this->has('is_featured')) {
            $this->merge([
                'is_featured' => filter_var($this->is_featured, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)
            ]);
        }

        // Ensure numeric types
        if ($this->has('min_price')) {
            $this->merge([
                'min_price' => is_numeric($this->min_price) ? (float) $this->min_price : $this->min_price
            ]);
        }

        if ($this->has('max_price')) {
            $this->merge([
                'max_price' => is_numeric($this->max_price) ? (float) $this->max_price : $this->max_price
            ]);
        }
    }
}
