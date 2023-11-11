<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RoleController extends Controller
{
    public function list()
    {
        $rolesWithPermissions = Role::with('permissions')->get();
        return view('roles.list', compact('rolesWithPermissions'));
    }


    public function create()
    {
        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
    }


    public function createProces(Request $request)
    {
        $role = Role::create(['name' => $request->role_name]);
        if (auth()->user()->can('sync permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('roles.list')->with('success', 'Szerepkör létrehozva!');
    }


    public function edit(string $roleName)
    {
        $role = Role::where('name', $roleName)->first();
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('name')->all();

        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }


    public function editProces(Request $request, string $roleName)
    {
        $role = Role::where('name', $roleName)->first();

        if ($role->name === 'superadmin') {
            return redirect()->route('roles.list')->with('danger_message', 'A <strong>superadmin</strong> szerepkör nem módosítható!');
            exit;
        }

        if ($role) {
            // név módosítás, ha a szerepkör létezik
            $role->name = $request->role_name;
            $role->save();

            // permissionok szinkronizálása ha felhasználónak van engedélye
            if (auth()->user()->can('sync permissions')) {
                $role->syncPermissions($request->permissions);
            }
        }

        return redirect()->route('roles.list')->with('success', 'Szerepkör módosítva!');
    }


    public function delete(string $roleName)
    {
        $role = Role::where('name', $roleName)->first();

        if ($role->name === 'superadmin') {
            return redirect()->route('roles.list')->with('danger_message', 'A <strong>superadmin</strong> szerepkör nem törölhető!');
            exit;
        }

        $role->delete();

        return redirect()->route('roles.list')->with('success', 'Szerepkör törölve!');
    }

}
