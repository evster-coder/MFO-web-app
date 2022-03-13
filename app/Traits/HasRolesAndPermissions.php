<?php

namespace App\Traits;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 * @property-read Collection|Role[] $roles
 * @property-read Collection|Permission[] $permissions
 */
trait HasRolesAndPermissions
{
    /**
     * Все роли пользователя
     *
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_role')->withTimestamps();
    }

    /**
     * Все права пользователя
     *
     * @return Collection
     */
    public function permissions(): Collection
    {
        $permissions = [];
        foreach ($this->roles as $role) {
            $perms = $role->permissions;
            foreach ($perms as $perm) {
                $permissions[] = $perm->slug;
            }
        }

        return collect(array_values(array_unique($permissions)));
    }

    /**
     * Проверка на наличие роли $role
     *
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        return $this->roles->contains('slug', $role);
    }

    /**
     * Проверка на наличие права $permission
     *
     * @param string $permission
     * @return bool
     */
    public function hasPermission(string $permission): bool
    {
        return $this->permissions()->contains($permission);
    }

    /**
     * Имеет текущий пользователь все права из $permissions
     *
     * @param ...$permissions
     * @return bool
     */
    public function hasAllPerms(...$permissions): bool
    {
        foreach ($permissions as $permission) {
            $condition = $this->hasPermission($permission);
            if (!$condition) {
                return false;
            }
        }

        return true;
    }

    /**
     * Имеет текущий пользователь любое право из $permissions
     *
     * @param ...$permissions
     * @return bool
     */
    public function hasAnyPerms(...$permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Назначить пользователю роли $roles
     *
     * @param ...$roles
     * @return $this
     */
    public function assignRoles(...$roles): self
    {
        $roles = Role::whereIn('slug', $roles)->get();

        if ($roles->count() === 0) {
            return $this;
        }

        $this->roles()->syncWithoutDetaching($roles);

        return $this;
    }

    /**
     * Удалить у пользователя роли $roles
     *
     * @param ...$roles
     * @return $this
     */
    public function unassignRoles(...$roles): self
    {
        $roles = Role::whereIn('slug', $roles)->get();
        if ($roles->count() === 0) {
            return $this;
        }
        $this->roles()->detach($roles);

        return $this;
    }
}
