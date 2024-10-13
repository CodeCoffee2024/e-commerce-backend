<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Order;

class StoreOrderRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'paymentOption' => ['required', 'string', 'in:CashOnDelivery'],
            'cartItems' => ['required','array'],
            'shippingAddress.id' => ['required', 'integer', 'exists:addresses,id'],
        ];
    }
    public function messages()
    {
        return [
            // Custom messages for 'paymentOption'
            'paymentOption.required' => 'Please select a valid payment option.',
            'paymentOption.integer' => 'The payment option must be a valid integer.',
            'paymentOption.in' => 'The selected payment option is not valid. Only Cash on Delivery is accepted.',
            
            // Custom messages for 'cartItems'
            'cartItems.required' => 'Your cart must contain at least one item.',
            'cartItems.array' => 'The cart items are not valid.',
            
            // Custom messages for 'shippingLocation'
            'shippingAddress.required' => 'Please provide a shipping location.',
            'shippingAddress.exists' => 'The selected shipping location does not exist in our records.',
        ];
    }
}
