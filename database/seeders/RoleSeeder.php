<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ── Roles globals (team_id = null) ──────────────────────────────
        setPermissionsTeamId(null);
        $roleSuperadmin = Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'web']);
        $roleClient = Role::firstOrCreate(['name' => 'client', 'guard_name' => 'web']);

        // ── Team fake ────────────────────────────────────────────────
        $team = Team::firstOrCreate(
            ['slug' => 'base-team'],
            [
                'name' => 'Base Team',
                'email' => 'info@base-team.com',
                'country' => 'ES',
                'timezone' => 'Europe/Madrid',
                'currency' => 'EUR',
                'is_active' => true,
            ]
        );

        // ── Roles by team ───────────────────────────────────────────────
        setPermissionsTeamId($team->id);
        $roleAdmin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $roleEmployee = Role::firstOrCreate(['name' => 'employee', 'guard_name' => 'web']);

        // ── Users ─────────────────────────────────────────────────────
        $superadmin = User::firstOrCreate(
            ['email' => 'superadmin@base.com'],
            ['name' => 'Super Admin', 'password' => Hash::make('password'), 'email_verified_at' => now()]
        );

        $admin = User::firstOrCreate(
            ['email' => 'admin@team.com'],
            ['name' => 'Admin', 'password' => Hash::make('password'), 'email_verified_at' => now()]
        );

        $employee = User::firstOrCreate(
            ['email' => 'employee@team.com'],
            ['name' => 'Employee', 'password' => Hash::make('password'), 'email_verified_at' => now()]
        );

        $client = User::firstOrCreate(
            ['email' => 'client@base.com'],
            ['name' => 'Client', 'password' => Hash::make('password'), 'email_verified_at' => now()]
        );

        // ── Role assignment ─────────────────────────────────────────────
        // Superadmin and client are global
        setPermissionsTeamId(null);
        $superadmin->assignRole($roleSuperadmin);
        $client->assignRole($roleClient);

        // Admin and employee belong to the team
        setPermissionsTeamId($team->id);
        $admin->assignRole($roleAdmin);
        $employee->assignRole($roleEmployee);
    }
}
