<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Models\UserRole;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressFragment extends JsonResource
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
            'zipCode' => $this->zipCode,
            'isDefaultDeliveryAddress' => $this->isMainDeliveryAddress ?? null,
            'blockLotFloorBuildingName' => $this->blockLotFloorBuildingName,
            'streetAddress' => $this->streetAddress,
            'barangay' => new BarangayFragment($this->barangay),
            'cityMunicipality' => new CityMunicipalityFragment($this->barangay->cityMunicipality),
            'province' => new ProvinceFragment($this->barangay->province),
            'region' => new RegionFragment($this->barangay->region)
        ];
    }
}