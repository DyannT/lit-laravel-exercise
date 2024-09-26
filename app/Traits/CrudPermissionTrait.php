<?php

namespace App\Traits;

use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Auth;

trait CrudPermissionTrait
{
    public array $operations = ['list', 'show', 'create', 'update', 'delete'];

    public function setAccessUsingPermissions()
    {
        $this->crud->denyAccess($this->operations);

        $table = CRUD::getModel()->getTable();
        $user = Auth::guard('backpack')->user();

        if (!$user) {
            return;
        }

        $role = $user->roles()->where('name', 'like', '%' . $table . '%')->first();
        $permissions = $role->getAllPermissions()->pluck('name')->toArray();
        $extraPermission = $user->permissions->pluck('name')->toArray();
        $mergedPermissions = array_unique(array_merge($permissions, $extraPermission));

        $this->crud->allowAccess($mergedPermissions);
    }
}
