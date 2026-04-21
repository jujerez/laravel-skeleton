<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::with('roles')->orderBy('name')->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        $roles = Role::orderBy('name')->get();

        return view('admin.users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->safe()->only('name', 'username', 'email', 'phone', 'password', 'is_active');

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::create($data);

        $this->syncRoles($user, $request->input('roles', []));

        return redirect()->route('admin.users.index')->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $user): View
    {
        $roles = Role::orderBy('name')->get();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->safe()->only('name', 'username', 'email', 'phone', 'is_active');

        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        $this->syncRoles($user, $request->input('roles', []));

        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado correctamente.');
    }

    /**
     * Sync roles for a user, respecting team scoping.
     *
     * @param  array<int>  $roleIds
     */
    private function syncRoles(User $user, array $roleIds): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $selectedRoles = Role::whereIn('id', $roleIds)->get();

        // Group selected roles by team_id (null = global)
        $byTeam = $selectedRoles->groupBy(fn (Role $role) => $role->team_id ?? '__null__');

        // Collect all team IDs present in the DB roles for this user so we can clear removed ones
        $allTeamIds = Role::distinct()->pluck('team_id')->unique();

        foreach ($allTeamIds as $teamId) {
            setPermissionsTeamId($teamId);

            $teamRoles = $byTeam->get($teamId === null ? '__null__' : $teamId, collect());

            $user->syncRoles($teamRoles);
        }

        setPermissionsTeamId(null);
    }
}
