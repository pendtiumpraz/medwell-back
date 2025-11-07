<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'Super Admin',
                'description' => 'Full system access with all permissions',
                'level' => 0,
                'is_system' => true,
                'permissions' => [
                    'patients.*', 'users.*', 'roles.*', 'vitals.*', 
                    'medications.*', 'alerts.*', 'reports.*', 'settings.*',
                    'organizations.*', 'facilities.*'
                ]
            ],
            [
                'name' => 'admin',
                'display_name' => 'Admin',
                'description' => 'Organization administrator with management permissions',
                'level' => 1,
                'is_system' => false,
                'permissions' => [
                    'patients.view', 'patients.create', 'patients.edit', 'patients.delete',
                    'users.view', 'users.create', 'users.edit',
                    'vitals.view', 'medications.view',
                    'alerts.view', 'reports.view', 'reports.export'
                ]
            ],
            [
                'name' => 'clinician',
                'display_name' => 'Clinician',
                'description' => 'Healthcare provider with patient care permissions',
                'level' => 2,
                'is_system' => false,
                'permissions' => [
                    'patients.view', 'patients.edit',
                    'vitals.view', 'vitals.create', 'vitals.edit',
                    'medications.view', 'medications.create', 'medications.edit',
                    'alerts.view', 'alerts.acknowledge', 'alerts.resolve',
                    'messages.send', 'messages.view'
                ]
            ],
            [
                'name' => 'patient',
                'display_name' => 'Patient',
                'description' => 'Patient with access to own health data',
                'level' => 3,
                'is_system' => true,
                'permissions' => [
                    'profile.view', 'profile.edit',
                    'vitals.view', 'vitals.create',
                    'medications.view', 'medications.consent',
                    'wearables.connect', 'wearables.sync',
                    'documents.view', 'messages.send', 'messages.view'
                ]
            ],
            [
                'name' => 'support',
                'display_name' => 'Support Staff',
                'description' => 'Support staff with limited access',
                'level' => 2,
                'is_system' => false,
                'permissions' => [
                    'patients.view', 'users.view',
                    'vitals.view', 'alerts.view',
                    'messages.view', 'messages.send'
                ]
            ],
            [
                'name' => 'manager',
                'display_name' => 'Manager',
                'description' => 'Facility manager with operational permissions',
                'level' => 1,
                'is_system' => false,
                'permissions' => [
                    'patients.view', 'users.view',
                    'vitals.view', 'medications.view',
                    'alerts.view', 'reports.view', 'reports.export',
                    'facilities.edit'
                ]
            ]
        ];

        foreach ($roles as $roleData) {
            Role::create($roleData);
        }

        $this->command->info('✅ Roles seeded successfully!');
    }
}
