<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Models\UserRole;
use Illuminate\Http\Resources\Json\JsonResource;

class MerchantFragment extends JsonResource
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
            'name' => $this->name
        ];
    }
}
