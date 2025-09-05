<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Tanent;
use App\Models\Client;
use App\Models\Expert;
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

        // Report permissions
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
            // 'view reports',
            // 'manage reports',

            // Settings permissions
            'view settings',
            'manage settings',

            // User & Profile permissions
            'view users',
            'view profile',
            'manage profile',

            // Payments permissions
            // 'view payments',
            // 'manage payments',
            // 'create payments',

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
            'update project status',

            // Task permissions
            'view tasks',
            'update tasks status',
            'manage tasks',
            'assign tasks',

            //Settings permissions
            'view settings',
            'manage settings',

            // User permissions
            'view users',

            // Profile permissions
            'view profile',
            'manage profile',

            // Files permissions
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

            // Report permissions
            'view reports',
            'manage reports',
        ],

        'expert' => [
            // Middleman permissions
            'view middleman',

            // Project and task permissions
            'view projects',
            'view tasks',
            'update tasks status',
            'update project status',

            // Files permissions
            'upload project deliverables',
            'manage project deliverables',

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
        // Create a tenant first (Middleman)
        // $tenantUser = User::factory()->create([
        //     'first_name' => 'Middleman User 1',
        //     'email' => 'middleman1@app.com',
        //     'password' => bcrypt('password'),
        // ]);
        // $tenantUser->assignRole('middleman');

        // Create the tenant record
        // $tenant = Tanent::create([
        //     'user_id' => $tenantUser->id,
        // ]);

        $users = [
            [
                'first_name' => 'Super Admin',
                'email' => 'superadmin@app.com',
                'password' => 'password',
                'role' => 'super-admin'
            ],
            // [
            //     'first_name' => 'Middleman User 2',
            //     'email' => 'middleman2@app.com',
            //     'password' => 'password',
            //     'role' => 'middleman',
            //     'tenant_data' => []
            // ],
            // [
            //     'first_name' => 'Expert User 1',
            //     'email' => 'expert1@app.com',
            //     'password' => 'password',
            //     'role' => 'expert',
            //     'expert_data' => [
            //         'tanent_id' => $tenant->id,
            //         'specialization' => 'Web Development',
            //     ]
            // ],
            // [
            //     'first_name' => 'Expert User 2',
            //     'email' => 'expert2@app.com',
            //     'password' => 'password',
            //     'role' => 'expert',
            //     'expert_data' => [
            //         'tanent_id' => $tenant->id,
            //         'specialization' => 'Mobile Development',
            //     ]
            // ],
            // [
            //     'first_name' => 'Client User 1',
            //     'email' => 'client1@app.com',
            //     'password' => 'password',
            //     'role' => 'client',
            //     'client_data' => [
            //         'tanent_id' => $tenant->id,
            //         'industry' => 'Client Company 1',
            //     ]
            // ],
            // [
            //     'first_name' => 'Client User 2',
            //     'email' => 'client2@app.com',
            //     'password' => 'password',
            //     'role' => 'client',
            //     'client_data' => [
            //         'tanent_id' => $tenant->id,
            //         'industry' => 'Client Company 2',
            //     ]
            // ],
        ];

        foreach ($users as $userData) {
            $user = User::factory()->create([
                'first_name' => $userData['first_name'],
                'email' => $userData['email'],
                'password' => bcrypt($userData['password']),
            ]);

            $user->assignRole($userData['role']);

            // Create related records based on role
            // if ($userData['role'] === 'middleman' && isset($userData['tenant_data'])) {
            //     Tanent::create(array_merge(
            //         $userData['tenant_data'],
            //         ['user_id' => $user->id]
            //     ));
            // }

            // if ($userData['role'] === 'expert' && isset($userData['expert_data'])) {
            //     Expert::create(array_merge(
            //         $userData['expert_data'],
            //         ['user_id' => $user->id]
            //     ));
            // }

            // if ($userData['role'] === 'client' && isset($userData['client_data'])) {
            //     Client::create(array_merge(
            //         $userData['client_data'],
            //         ['user_id' => $user->id]
            //     ));
            // }
        }
    }
}
