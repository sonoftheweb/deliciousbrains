<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'description' => $this->description,
            'amount' => $this->amount,
            'activity_date' => $this->activity_date,
            'group' => Carbon::parse($this->activity_date)->format('Y-m-d')
        ];
    }
}
