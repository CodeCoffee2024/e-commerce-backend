<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Models\UserRole;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'isActive' => $this->isActive,
            'createdAt' => $this->created_at,
            'isGoogleAccount' => $this->isGoogleAccount,
            'isFacebookAccount' => $this->isFacebookAccount,
            'email_verified_at' => $this->email_verified_at,
            'google' => UserGoogleFragment::make($this->google),
        ];
    }
}
