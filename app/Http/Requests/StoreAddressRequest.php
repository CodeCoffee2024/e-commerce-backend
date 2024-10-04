<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'blockLotFloorBuildingName' => ['required','string','max:100'],
            'streetAddress' => ['required','string','max:100'],
            'barangay' => ['required', 'array'],
            'id' => ['nullable', 'numeric'],
            'barangay.id' => ['required', 'integer'], // Assuming the id is an integer
            'barangay.description' => ['required', 'string'],
            'cityMunicipality' =>  ['required', 'array'],
            'cityMunicipality.id' => ['required', 'integer'], // Assuming the id is an integer
            'cityMunicipality.description' => ['required', 'string'],
            'province' =>  ['required', 'array'],
            'province.id' => ['required', 'integer'], // Assuming the id is an integer
            'province.description' => ['required', 'string'],
            'region' =>  ['required', 'array'],
            'region.id' => ['required', 'integer'], // Assuming the id is an integer
            'region.description' => ['required', 'string'],
            'zipCode' => 'required',
            'isDefaultDeliveryAddress' => ['required', 'boolean'],
            'isDefaultReturnAddress' => ['required', 'boolean'],
        ];
    }
    public function messages()
    {
        return [
            'blockLotFloorBuildingName.required' => 'House #, Blk #, Lt #, Flr #, Bldg. Name is required',
            'streetAddress.required' => 'Street name is required',
            'cityMunicipality.required' => 'City/Municipality is required',
            'barangay.required' => 'Barangay is required',
            'province.required' => 'Province is required',
            'region.required' => 'Region is required',
            'zipCode.required' => 'Zipcode is required',
            'isDefaultDeliveryAddress.required' => 'Default delivery address is required',
            'isDefaultReturnAddress.required' => 'Default return address is required',
        ];
    }
}
