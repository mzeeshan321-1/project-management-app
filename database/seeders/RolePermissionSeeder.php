<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    // Flat permission list (all permissions here)
    private array $allPermissions = [
        // General permissions
        'view middleman',
        'manage middleman',

        'view reports',
        'manage reports',

        'view settings',
        'manage settings',

        // User permissions
        'view users',
        'view profile',
        'manage profile',

        // Client permissions
        'manage clients',

        // Expert permissions
        'manage experts',

        // Project and task permissions
        'view projects',
        'manage projects',
        'assign projects',

        'view tasks', 
        'update tasks status', 
        'manage tasks', 
        'assign tasks',

        'update project status',
        'upload project deliverables',
        'manage project deliverables',

        // Transactions and payments
        'view payments', 
        'manage payments',
        'create payments',

        // Expenses
        'view expenses', 
        'manage expenses',

        // Client-side actions
        'request new projects', 
        'update project deliverables',

        'rate experts',

        // Expert-only
        'update availability',
    ];

    // Role-permission mapping
    private array $rolePermissions = [
        'super-admin' => [
            // Middleman permissions
            'view middleman',
            'manage middleman',

            // Reports permissions
            'view reports',
            'manage reports',

            // Settings permissions
            'view settings',
            'manage settings',
            
            // User & Profile permissions
            'view users',
            'view profile',
            'manage profile',

            // Payments permissions
            'view payments', 
            'manage payments',
            'create payments',

            // Expenses permissions
            'view expenses', 
            'manage expenses',

        ],

        'middleman' => [
             // Client permissions
            'manage clients',
            
            // Expert permissions
            'manage experts',

            // Project permissions
            'view projects',
            'manage projects', 
            'assign projects',

            // Task permissions
            'view tasks', 
            'manage tasks', 

            //Settings permissions
            'view settings',
            'manage settings',
            
            // User permissions
            'view users',

            // Profile permissions
            'view profile',
            'manage profile',

            // Files permissions
            'update project status',
            'upload project deliverables',
            'manage project deliverables',

            // Payments permissions
            'view payments', 
            'manage payments',
            'create payments',

            // Expenses permissions
            'view expenses', 
            'manage expenses',
            
            // User Status permissions
            'update availability',

        ],

        'expert' => [
            // Middleman permissions
            'view middleman',

            // Project and task permissions
            'view projects',
            'view tasks', 
            'update tasks status',
            
            // Files permissions
            'upload project deliverables',
            'manage project deliverables',
            'update project status',

            // payments permissions
            'view payments', 

            // User Status permissions
            'update availability',

            // Settings permissions
            'view settings',

            // Profile permissions
            'view profile',
        ],

        'client' => [
            // Middleman permissions
            'view middleman',
            
            // Files permissions
            'update project deliverables',

            // Project and task permissions
            'request new projects', 
            'view projects',
            // 'view tasks', -----------

            // Settings permissions
            'view settings',

            // Payments permissions
            'view payments',
            'manage payments',
            'create payments',

            // Expenses permissions
            'view expenses', 
            'manage expenses',

            // Expert permissions
            'rate experts',

            // Profile permissions
            'view profile',
        ],
    ];

    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create all permissions
        foreach ($this->allPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create roles and assign their respective permissions
        foreach ($this->rolePermissions as $roleName => $permissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($permissions);
        }

        // Optional: Create demo users and assign roles
        $this->createDemoUsers();
    }

    protected function createDemoUsers()
    {
        $users = [
            ['first_name' => 'Super Admin', 'email' => 'superadmin@app.com', 'password' => 'password', 'role' => 'super-admin'],
            ['first_name' => 'Middleman User 1', 'email' => 'middleman1@app.com', 'password' => 'password', 'role' => 'middleman'],
            ['first_name' => 'Middleman User 2', 'email' => 'middleman2@app.com', 'password' => 'password', 'role' => 'middleman'],
            ['first_name' => 'Expert User 1', 'email' => 'expert1@app.com', 'password' => 'password', 'role' => 'expert'],
            ['first_name' => 'Expert User 2', 'email' => 'expert2@app.com', 'password' => 'password', 'role' => 'expert'],
            ['first_name' => 'Client User 1', 'email' => 'client1@app.com', 'password' => 'password', 'role' => 'client'],
            ['first_name' => 'Client User 2', 'email' => 'client2@app.com', 'password' => 'password', 'role' => 'client'],
        ];

        foreach ($users as $userData) {
            $user = User::factory()->create([
                'first_name' => $userData['first_name'],
                'email' => $userData['email'],
                'password' => bcrypt($userData['password']),
            ]);

            $user->assignRole($userData['role']);
        }
    }
}
