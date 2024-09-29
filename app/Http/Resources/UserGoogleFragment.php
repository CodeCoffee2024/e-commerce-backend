<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Models\UserRole;
use Illuminate\Http\Resources\Json\JsonResource;

class UserGoogleFragment extends JsonResource
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
            'federatedId' => $this->federatedId,
            'emailVerified' => $this->emailVerified,
            'firstName' => $this->firstName,
            'fullName' => $this->fullName,
            'lastName' => $this->lastName,
            'photoUrl' => $this->photoUrl,
            'localId' => $this->localId,
            'displayName' => $this->displayName,
            'idToken' => $this->idToken,
            'oauthAccessToken' => $this->oauthAccessToken,
        ];
    }
}
