<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\AjaxForm;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::with('roles')->orderBy('name')->get();

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        $roles = Role::orderBy('name')->get();

        return view('admin.users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $data = $request->safe()->only('name', 'username', 'email', 'phone', 'password', 'is_active');

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::create($data);

        $this->syncRoles($user, $request->input('roles', []));

        // return redirect()->route('admin.users.index')->with('success', 'Usuario creado correctamente.');
        return AjaxForm::custom([
            'entryUrl' => route('admin.users.edit', $user),
            'message' => 'Usuario creado correctamente.',
        ])->jsonResponse();
    }

    public function edit(User $user): View
    {
        $roles = Role::orderBy('name')->get();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
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

        return AjaxForm::custom([
            'entryUrl' => route('admin.users.edit', $user),
            'message' => 'Usuario actualizado correctamente.',
        ])->jsonResponse();
    }

    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return AjaxForm::redirection(route('admin.users.index'))->jsonResponse();
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
