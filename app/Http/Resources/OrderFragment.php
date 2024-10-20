<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
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
            'id' => $this->id,
            'totalPrice' => $this->totalPrice,
            'referenceNumber' => $this->reference_number,
            'totalShipping' => $this->totalShipping,
            'paymentOption' => $this->paymentOption,
            'grandTotal' => $this->totalShipping + $this->totalPrice,
            'status' => $this->status,
            'customer' => new UserFragment($this->user),
            'shipTo' => new CityMunicipalityFragment($this->shippingAddress->barangay->cityMunicipality),
            'cart' => json_decode($this->cart)
        ];
    }
}
