<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Models\UserRole;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductFragment extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'productName' => $this->name,
            'productDescription' => $this->description,
            'productImgPath' => $this->imgPath,
            'id' => $this->id,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'categoryId' => $this->category->id,
            'isActive' => $this->isActive,
            'category' => $this->category,
            'merchant' => $this->merchant,
        ];
    }
}