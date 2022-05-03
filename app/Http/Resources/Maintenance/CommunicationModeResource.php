<?php

namespace App\Http\Resources\Maintenance;

use Illuminate\Http\Resources\Json\JsonResource;

use Carbon\Carbon;

class CommunicationModeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'short_name' => $this->short_name,
            'description' => $this->description,
            'created_at' => Carbon::parse($this->created_at)->format('F j, Y'),
        ];
    }
}
