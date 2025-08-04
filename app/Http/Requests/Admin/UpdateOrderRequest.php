<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check() && auth()->user()->hasRole(['admin', 'manager']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled,refunded',
            'payment_status' => 'nullable|in:pending,paid,failed,refunded',
            'tracking_number' => 'nullable|string|max:255',
            'payment_method' => 'nullable|string|max:50',
            'delivery_method' => 'nullable|string|max:50',
            'delivery_fee' => 'nullable|numeric|min:0',
            'delivery_date' => 'nullable|date',
            'delivery_time_slot' => 'nullable|string|max:100',
            'order_notes' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'status.required' => 'Buyurtma holati majburiy.',
            'status.in' => 'Noto\'g\'ri buyurtma holati.',
            'payment_status.in' => 'Noto\'g\'ri to\'lov holati.',
            'tracking_number.max' => 'Kuzatuv raqami 255 belgidan oshmasligi kerak.',
            'delivery_fee.numeric' => 'Yetkazib berish narxi raqam bo\'lishi kerak.',
            'delivery_fee.min' => 'Yetkazib berish narxi manfiy bo\'lishi mumkin emas.',
            'delivery_date.date' => 'Noto\'g\'ri sana formati.',
            'order_notes.max' => 'Eslatmalar 1000 belgidan oshmasligi kerak.',
            'notes.max' => 'Izoh 500 belgidan oshmasligi kerak.',
        ];
    }
}
