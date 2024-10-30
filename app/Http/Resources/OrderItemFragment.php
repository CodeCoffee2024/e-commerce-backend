<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\UserRole;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemFragment extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'product' => new ProductFragment($this->product),
            'price' => $this->price,
            'quantity' => $this->quantity,
            'status' => $this->status,
            'canSetAsToShip' => $this->status == Order::STATUS_PENDING,
            'canSetAsForDelivery' => $this->status == Order::STATUS_TO_SHIP,
        ];
    }
}
