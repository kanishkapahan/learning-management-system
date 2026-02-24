<?php

namespace App\Models\Concerns;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait HasAppRoles
{
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class)->withTimestamps();
    }

    public function hasRole(string $role): bool
    {
        return $this->roles->contains(fn (Role $item) => $item->name === $role);
    }

    public function canDo(string $permission): bool
    {
        return $this->permissions->contains(fn (Permission $item) => $item->name === $permission)
            || $this->roles->contains(fn (Role $role) => $role->permissions->contains('name', $permission));
    }

    public function assignRole(string|Role $role): void
    {
        $roleModel = $role instanceof Role ? $role : Role::query()->where('name', $role)->firstOrFail();
        $this->roles()->syncWithoutDetaching([$roleModel->id]);
    }
}
