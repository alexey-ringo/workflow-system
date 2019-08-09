<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Models\Role;
use App\Models\Group;
use App\Http\Resources\Role\RoleResource;
use App\Http\Resources\Group\GroupResource;

class UserRelationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            //'roles' => RoleResource::collection($this->whenLoaded('roles')),
            //'permissions' => PermissionResource::collection($this->whenLoaded('permissions')),
            //'groups' => GroupResource::collection($this->whenLoaded('groups')),
            //'missions' => MissionResource::collection($this->whenLoaded('missions')),
            'roles' => RoleResource::collection($this->roles),
            'all_roles' => RoleResource::collection(Role::all()),
            'groups' => GroupResource::collection($this->groups),
            'all_groups' => GroupResource::collection(Group::all())
        ];
    }
}
