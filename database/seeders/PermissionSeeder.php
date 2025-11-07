<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Patients Permissions
            ['name' => 'patients.view', 'display_name' => 'View Patients', 'description' => 'View patient profiles and information', 'group' => 'patients'],
            ['name' => 'patients.create', 'display_name' => 'Create Patients', 'description' => 'Create new patient accounts', 'group' => 'patients'],
            ['name' => 'patients.edit', 'display_name' => 'Edit Patients', 'description' => 'Update patient information', 'group' => 'patients'],
            ['name' => 'patients.delete', 'display_name' => 'Delete Patients', 'description' => 'Soft delete patient accounts', 'group' => 'patients'],
            ['name' => 'patients.*', 'display_name' => 'All Patient Actions', 'description' => 'Full access to patient management', 'group' => 'patients'],

            // Users Permissions
            ['name' => 'users.view', 'display_name' => 'View Users', 'description' => 'View user accounts and profiles', 'group' => 'users'],
            ['name' => 'users.create', 'display_name' => 'Create Users', 'description' => 'Create new user accounts', 'group' => 'users'],
            ['name' => 'users.edit', 'display_name' => 'Edit Users', 'description' => 'Update user information', 'group' => 'users'],
            ['name' => 'users.delete', 'display_name' => 'Delete Users', 'description' => 'Delete user accounts', 'group' => 'users'],
            ['name' => 'users.*', 'display_name' => 'All User Actions', 'description' => 'Full access to user management', 'group' => 'users'],

            // Roles Permissions
            ['name' => 'roles.view', 'display_name' => 'View Roles', 'description' => 'View role definitions', 'group' => 'roles'],
            ['name' => 'roles.create', 'display_name' => 'Create Roles', 'description' => 'Create new roles', 'group' => 'roles'],
            ['name' => 'roles.edit', 'display_name' => 'Edit Roles', 'description' => 'Update role permissions', 'group' => 'roles'],
            ['name' => 'roles.delete', 'display_name' => 'Delete Roles', 'description' => 'Delete custom roles', 'group' => 'roles'],
            ['name' => 'roles.*', 'display_name' => 'All Role Actions', 'description' => 'Full access to role management', 'group' => 'roles'],

            // Vitals Permissions
            ['name' => 'vitals.view', 'display_name' => 'View Vitals', 'description' => 'View patient vital signs', 'group' => 'vitals'],
            ['name' => 'vitals.create', 'display_name' => 'Record Vitals', 'description' => 'Record new vital signs', 'group' => 'vitals'],
            ['name' => 'vitals.edit', 'display_name' => 'Edit Vitals', 'description' => 'Update vital sign records', 'group' => 'vitals'],
            ['name' => 'vitals.delete', 'display_name' => 'Delete Vitals', 'description' => 'Delete vital sign records', 'group' => 'vitals'],
            ['name' => 'vitals.*', 'display_name' => 'All Vital Actions', 'description' => 'Full access to vital signs', 'group' => 'vitals'],

            // Medications Permissions
            ['name' => 'medications.view', 'display_name' => 'View Medications', 'description' => 'View medication prescriptions', 'group' => 'medications'],
            ['name' => 'medications.create', 'display_name' => 'Prescribe Medications', 'description' => 'Create new prescriptions', 'group' => 'medications'],
            ['name' => 'medications.edit', 'display_name' => 'Edit Medications', 'description' => 'Update prescriptions', 'group' => 'medications'],
            ['name' => 'medications.delete', 'display_name' => 'Delete Medications', 'description' => 'Remove prescriptions', 'group' => 'medications'],
            ['name' => 'medications.consent', 'display_name' => 'Consent Medications', 'description' => 'Accept or decline medications', 'group' => 'medications'],
            ['name' => 'medications.*', 'display_name' => 'All Medication Actions', 'description' => 'Full medication management', 'group' => 'medications'],

            // Alerts Permissions
            ['name' => 'alerts.view', 'display_name' => 'View Alerts', 'description' => 'View health alerts', 'group' => 'alerts'],
            ['name' => 'alerts.acknowledge', 'display_name' => 'Acknowledge Alerts', 'description' => 'Mark alerts as acknowledged', 'group' => 'alerts'],
            ['name' => 'alerts.resolve', 'display_name' => 'Resolve Alerts', 'description' => 'Mark alerts as resolved', 'group' => 'alerts'],
            ['name' => 'alerts.*', 'display_name' => 'All Alert Actions', 'description' => 'Full alert management', 'group' => 'alerts'],

            // Reports Permissions
            ['name' => 'reports.view', 'display_name' => 'View Reports', 'description' => 'Access analytics and reports', 'group' => 'reports'],
            ['name' => 'reports.export', 'display_name' => 'Export Reports', 'description' => 'Export reports to PDF/Excel', 'group' => 'reports'],
            ['name' => 'reports.*', 'display_name' => 'All Report Actions', 'description' => 'Full report access', 'group' => 'reports'],

            // Settings Permissions
            ['name' => 'settings.view', 'display_name' => 'View Settings', 'description' => 'View system settings', 'group' => 'settings'],
            ['name' => 'settings.edit', 'display_name' => 'Edit Settings', 'description' => 'Modify system settings', 'group' => 'settings'],
            ['name' => 'settings.*', 'display_name' => 'All Settings Actions', 'description' => 'Full settings access', 'group' => 'settings'],

            // Organizations Permissions
            ['name' => 'organizations.view', 'display_name' => 'View Organizations', 'description' => 'View organization details', 'group' => 'organizations'],
            ['name' => 'organizations.create', 'display_name' => 'Create Organizations', 'description' => 'Create new organizations', 'group' => 'organizations'],
            ['name' => 'organizations.edit', 'display_name' => 'Edit Organizations', 'description' => 'Update organization info', 'group' => 'organizations'],
            ['name' => 'organizations.delete', 'display_name' => 'Delete Organizations', 'description' => 'Remove organizations', 'group' => 'organizations'],
            ['name' => 'organizations.*', 'display_name' => 'All Organization Actions', 'description' => 'Full organization access', 'group' => 'organizations'],

            // Facilities Permissions
            ['name' => 'facilities.view', 'display_name' => 'View Facilities', 'description' => 'View facility information', 'group' => 'facilities'],
            ['name' => 'facilities.create', 'display_name' => 'Create Facilities', 'description' => 'Add new facilities', 'group' => 'facilities'],
            ['name' => 'facilities.edit', 'display_name' => 'Edit Facilities', 'description' => 'Update facility details', 'group' => 'facilities'],
            ['name' => 'facilities.delete', 'display_name' => 'Delete Facilities', 'description' => 'Remove facilities', 'group' => 'facilities'],
            ['name' => 'facilities.*', 'display_name' => 'All Facility Actions', 'description' => 'Full facility management', 'group' => 'facilities'],

            // Messages Permissions
            ['name' => 'messages.view', 'display_name' => 'View Messages', 'description' => 'View messages and chats', 'group' => 'messages'],
            ['name' => 'messages.send', 'display_name' => 'Send Messages', 'description' => 'Send messages to users', 'group' => 'messages'],
            ['name' => 'messages.*', 'display_name' => 'All Message Actions', 'description' => 'Full messaging access', 'group' => 'messages'],

            // Profile Permissions
            ['name' => 'profile.view', 'display_name' => 'View Profile', 'description' => 'View own profile', 'group' => 'profile'],
            ['name' => 'profile.edit', 'display_name' => 'Edit Profile', 'description' => 'Update own profile', 'group' => 'profile'],

            // Wearables Permissions
            ['name' => 'wearables.connect', 'display_name' => 'Connect Wearables', 'description' => 'Connect wearable devices', 'group' => 'wearables'],
            ['name' => 'wearables.sync', 'display_name' => 'Sync Wearables', 'description' => 'Sync wearable data', 'group' => 'wearables'],
            ['name' => 'wearables.*', 'display_name' => 'All Wearable Actions', 'description' => 'Full wearable management', 'group' => 'wearables'],

            // Documents Permissions
            ['name' => 'documents.view', 'display_name' => 'View Documents', 'description' => 'View health documents', 'group' => 'documents'],
            ['name' => 'documents.upload', 'display_name' => 'Upload Documents', 'description' => 'Upload new documents', 'group' => 'documents'],
            ['name' => 'documents.delete', 'display_name' => 'Delete Documents', 'description' => 'Remove documents', 'group' => 'documents'],
            ['name' => 'documents.*', 'display_name' => 'All Document Actions', 'description' => 'Full document management', 'group' => 'documents'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        $this->command->info('✅ ' . count($permissions) . ' Permissions seeded successfully!');
    }
}
