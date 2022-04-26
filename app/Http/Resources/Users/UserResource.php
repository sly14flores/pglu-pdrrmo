<?php

namespace App\Http\Resources\Users;

use Illuminate\Http\Resources\Json\JsonResource;

use Carbon\Carbon;

class UserResource extends JsonResource
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
            'firstname' => $this->firstname,
            'middlename' => $this->middlename,
            'lastname' => $this->lastname,
            'name' => "{$this->firstname} {$this->lastname}",
            'email' => $this->email,
            'group_id' => $this->group_id,
            'role' => (is_null($this->group))?'':$this->group->name,
            'created_at' => Carbon::parse($this->created_at)->format('F j, Y'),
        ];
    }
}
