<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\UserRole;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderShippingMerchantFragment extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'merchant' => new MerchantFragment($this->merchant),
            'shippingFee' => $this->pivot->shipping_cost,
        ];
    }
}
