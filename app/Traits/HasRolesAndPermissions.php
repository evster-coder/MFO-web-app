<?php

namespace App\Traits;

use App\Models\Role;
use App\Models\Permission;

trait HasRolesAndPermissions {

    /**
    //все роли пользователя
    */
    public function roles() {
        return $this
            ->belongsToMany(Role::class, 'user_role')
            ->withTimestamps();
    }

    public function permissions() {
        return $this->roles->permissions();
    }

    /**
    //проверка на наличие роли $role
    */
    public function hasRole($role) {
        return $this->roles->contains('slug', $role);
    }

    /**
    //проверка на наличие права $permission*
    */
    public function hasPermission($permission) {
        return $this->permissions->contains('slug', $permission);
    }

    /**
    //Имеет текущий пользователь все права из $permissions
    */
    public function hasAllPerms(...$permissions) {
        foreach ($permissions as $permission) {
            $condition = $this->hasPermission($permission);
            if (!$condition) {
                return false;
            }
        }
        return true;
    }

    /**
    //Имеет текущий пользователь любое право из $permissions
    */
    public function hasAnyPerms(...$permissions) {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    /**
    //Возвращает массив всех прав пользователя
    */
    public function getAllPerms() {
        $permissions = [];
        foreach ($this->roles as $role) {
            $perms = $role->permissions;
            foreach ($perms as $perm) {
                $permissions[] = $perm->slug;
            }
        }
        return array_values(array_unique($permissions));
    }

    /**
    //Возвращает массив всех ролей текущего пользователя
    */
    public function getAllRoles() {
        return $this->roles->pluck('slug')->toArray();
    }

    /**
    //Добавить пользователю роли $roles
    */
    public function assignRoles(...$roles) {
        $roles = Role::whereIn('slug', $roles)->get();
        if ($roles->count() === 0) {
            return $this;
        }
        $this->roles()->syncWithoutDetaching($roles);
        return $this;
    }

    /**
    // Удалить у пользователя роли $roles
    */
    public function unassignRoles(...$roles) {
        $roles = Role::whereIn('slug', $roles)->get();
        if ($roles->count() === 0) {
            return $this;
        }
        $this->roles()->detach($roles);
        return $this;
    }
}