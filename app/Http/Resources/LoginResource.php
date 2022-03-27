<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

// use App\Roles\Authorizations;

class LoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        // $authorizations = new Authorizations();
        // $role = (is_null($this->group)) ? null : ((is_null($this->group->role))?$authorizations->template():$this->group->role);

        return [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'token' => $this->token,
            'group_id' => (is_null($this->group))?null:$this->group->id,
            // 'group_name' => (is_null($this->group))?null:$this->group->name,
            'default_password' => $this->default_password,
            'email_verified' => $this->email_verified,
            // 'role' => $authorizations->decode($role),
        ];
    }
}
