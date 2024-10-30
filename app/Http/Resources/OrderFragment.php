<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\UserRole;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderFragment extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->order_id,
            'totalPrice' => $this->totalPrice,
            'referenceNumber' => $this->reference_number,
            'totalShipping' => $this->totalShipping,
            'paymentOption' => Order::$paymentOptionLabels[$this->paymentOption],
            'grandTotal' => $this->totalShipping + $this->totalPrice,
            'status' => Order::STATUS_LABELS[$this->status],
            'customer' => new UserFragment($this->user),
            'shippingMerchant' => OrderShippingMerchantFragment::collection($this->shippingMerchants),
            'canSetAsToShip' => $this->status == Order::STATUS_PENDING,
            'canSetAsForDelivery' => $this->status == Order::STATUS_TO_SHIP,
            'createdAt' => $this->created_at,
            'shipTo' => new CityMunicipalityFragment($this->shippingAddress->barangay->cityMunicipality),
            'items' => OrderItemFragment::collection($this->items)
        ];
    }
}
