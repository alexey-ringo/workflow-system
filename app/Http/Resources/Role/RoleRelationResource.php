<?php

namespace App\Http\Resources\Role;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Permission;
use App\Http\Resources\Permission\PermissionResource;

class RoleRelationResource extends JsonResource
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
            'title' => $this->title,
            'name' => $this->name,
            //'permissions' => PermissionResource::collection($this->whenLoaded('permissions')),
            'permissions' => PermissionResource::collection($this->permissions),
            'all_permissions' => PermissionResource::collection(Permission::all())
        ];
    }
}
