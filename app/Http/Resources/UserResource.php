<?php

namespace App\Http\Resources;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'profile' => $this->whenLoaded('profile')
        ];

        if (is_a($this->whenLoaded('accountActivity'), Collection::class)) {
            $data['account_activity'] = AccountActivity::collection($this->accountActivity);
        }

        return $data;
    }
}
