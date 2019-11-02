<?php
namespace App\Models\Traits;

use App\Models\Permission;
use App\Models\Role;

trait RolePermissionsTrait {

    //$require = true - обязательное совпадение всех permission в массиве $permission (если от массив)
    //$require = false - достаточно совпадение хотя бы одного permission в $permission
    public function canDo($permission, $require = false) {
        //Если $permission - это массив:
        if(is_array($permission)) {
            foreach($permission as $permName) {
                $permName = $this->canDo($permName);
                if($permName && !$require) {
                    return true;
                }
                else if(!$permName && $require) {
                    return false;
                }
            }
            //Если на каждой итерации цикла $this->canDo($permName) всегда вернет true
            //т.е. - ги одно из условий выше не будет выполнено
            //и входящее $require == true
            return $require;            
        }
        //Если $permission - это строка
        else {
            foreach($this->roles as $role) {
                foreach($role->permissions as $perm) {
                    //Поиск совпадения по маске
                    if(str_is($permission, $perm->name)) {
                        return true;
                    }
                }
            }
        }
    }
    
    
    public function hasRole($name, $require = false) {
        //Если $name - это массив:
        if(is_array($name)) {
            foreach($name as $roleName) {
                $hasRole = $this->canDo($roleName);
                if($hasRole && !$require) {
                    return true;
                }
                else if(!$hasRole && $require) {
                    return false;
                }
            }
            return $require;            
        }
        //Если $name - это строка
        else {
            foreach($this->roles as $role) {
                if($role->name == $name) {
                    return true;
                }
            }
        }
        return false;
    }
}