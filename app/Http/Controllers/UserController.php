<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function list()
    {
        // minden elem lista
        $usersWithRoles = User::with('roles')->get();
        return view('users.list', compact('usersWithRoles'));
    }


    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }


    public function createProces(Request $request)
    {

        $request->validate(
            [
                'name' => 'unique:users',
                'email' => 'email|regex:/^.+@.+\..+$/i|unique:users',
                'password' => 'min:8',
            ],
            [
                "name.unique" => "Már van ilyen nevű felhasználó",
                'email.email' => 'Az email cím formátuma nem megfelelő.',
                "email.regex" => "Az email cím formátuma érvénytelen",
                'email.unique' => 'Ez az email cím már regisztrálva van.',
                "password.min" => "Jelszó min. 8 karakter legyen"
            ]
        );

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'updated_at' => null,
            'created_at' => now(),

        ]);

        if (auth()->user()->can('sync roles')) {
            $user->syncRoles($request->roles);
        }

        return redirect()->route('users.list')->with('success', 'Felhasználó létrehozva!');
    }


    public function edit(string $userName)
    {
        $user = User::where('name', $userName)->first();
        $roles = Role::all();
        $userRoles = $user->roles->pluck('name')->all();

        return view('users.edit', compact('user', 'roles', 'userRoles'));
    }


    public function editProces(Request $request)
    {
        // dd($request->roles);
        // exit;
        $user = User::where('id', $request->id)->first();

        if ($user->name === 'superadmin') {
            return redirect()->route('users.list')->with('danger_message', 'A <strong>superadmin</strong> felhasználó nem módosítható!');
            exit;
        }

        $request->validate(
            [
                'name' => [
                    Rule::unique('users', 'name')->ignore($request->id),
                ],
                'email' => [
                    'email',
                    'regex:/^.+@.+\..+$/i',
                    Rule::unique('users', 'email')->ignore($request->id),
                ],
                'password' => 'nullable|min:8'
            ],
            [
                "name.unique" => "Már van ilyen nevű felhasználó",
                'email.email' => 'Az email cím formátuma nem megfelelő.',
                "email.regex" => "Az email cím formátuma érvénytelen",
                'email.unique' => 'Ez az email cím már regisztrálva van.',
                "password.min" => "Jelszó min. 8 karakter legyen"
            ]

        );

        $user->name = $request->name;
        $user->email = $request->email;
        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }
        $user->updated_at = now();
        $user->save();

        if (auth()->user()->can('sync roles')) {
            $user->syncRoles($request->roles);
        }

        return redirect()->route('users.list')->with('success', 'Felhasználó módosítva!');
    }


    public function delete(string $userName)
    {
        $user = User::where('name', $userName)->first();

        if ($user->name === 'superadmin') {
            return redirect()->route('users.list')->with('danger_message', 'A <strong>superadmin</strong> felhasználó nem törölhető!');
            exit;
        }

        $user->delete();

        return redirect()->route('users.list')->with('success', 'Felhasználó törölve!');
    }


}
