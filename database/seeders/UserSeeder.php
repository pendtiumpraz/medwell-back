<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Organization;
use App\Models\PatientProfile;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $bioFarma = Organization::where('name', 'Bio Farma')->first();
        $partner = Organization::where('name', 'Rumah Sakit Umum Jakarta')->first();

        // Super Admin
        $superAdmin = User::create([
            'username' => 'superadmin',
            'email' => 'superadmin@medwell.id',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
            'organization_id' => $bioFarma->id,
            'status' => 'active',
            'email_verified_at' => now()
        ]);
        $superAdmin->roles()->attach(Role::where('name', 'super_admin')->first());

        // Admin Bio Farma
        $admin = User::create([
            'username' => 'admin_biofarma',
            'email' => 'admin@biofarma.co.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'organization_id' => $bioFarma->id,
            'status' => 'active',
            'email_verified_at' => now()
        ]);
        $admin->roles()->attach(Role::where('name', 'admin')->first());

        // Clinician 1 - Cardiologist
        $clinician1 = User::create([
            'username' => 'dr_sarah',
            'email' => 'sarah.cardio@biofarma.co.id',
            'password' => Hash::make('password123'),
            'role' => 'clinician',
            'organization_id' => $bioFarma->id,
            'status' => 'active',
            'email_verified_at' => now()
        ]);
        $clinician1->roles()->attach(Role::where('name', 'clinician')->first());

        // Clinician 2 - General Practitioner
        $clinician2 = User::create([
            'username' => 'dr_budi',
            'email' => 'budi.gp@biofarma.co.id',
            'password' => Hash::make('password123'),
            'role' => 'clinician',
            'organization_id' => $bioFarma->id,
            'status' => 'active',
            'email_verified_at' => now()
        ]);
        $clinician2->roles()->attach(Role::where('name', 'clinician')->first());

        // Support Staff
        $support = User::create([
            'username' => 'support_team',
            'email' => 'support@biofarma.co.id',
            'password' => Hash::make('password123'),
            'role' => 'support',
            'organization_id' => $bioFarma->id,
            'status' => 'active',
            'email_verified_at' => now()
        ]);
        $support->roles()->attach(Role::where('name', 'support')->first());

        // Sample Patients
        $patients = [
            [
                'username' => 'john_doe',
                'email' => 'john.doe@email.com',
                'profile' => [
                    'full_name' => 'John Doe',
                    'date_of_birth' => '1985-05-15',
                    'gender' => 'male',
                    'phone' => '+62812345678',
                    'address' => 'Jl. Merdeka No.10, Jakarta',
                    'height' => 175,
                    'weight' => 75,
                    'blood_type' => 'O+',
                    'wearable_type' => 'fitbit',
                    'onboarding_completed' => true
                ]
            ],
            [
                'username' => 'jane_smith',
                'email' => 'jane.smith@email.com',
                'profile' => [
                    'full_name' => 'Jane Smith',
                    'date_of_birth' => '1990-08-22',
                    'gender' => 'female',
                    'phone' => '+62823456789',
                    'address' => 'Jl. Sudirman No.50, Jakarta',
                    'height' => 162,
                    'weight' => 58,
                    'blood_type' => 'A+',
                    'wearable_type' => 'huawei',
                    'onboarding_completed' => true
                ]
            ],
            [
                'username' => 'ahmad_rizki',
                'email' => 'ahmad.rizki@email.com',
                'profile' => [
                    'full_name' => 'Ahmad Rizki',
                    'date_of_birth' => '1978-03-10',
                    'gender' => 'male',
                    'phone' => '+62834567890',
                    'address' => 'Jl. Asia Afrika No.100, Bandung',
                    'height' => 168,
                    'weight' => 80,
                    'blood_type' => 'B+',
                    'wearable_type' => 'apple',
                    'onboarding_completed' => true
                ]
            ],
            [
                'username' => 'siti_nurhaliza',
                'email' => 'siti.nurhaliza@email.com',
                'profile' => [
                    'full_name' => 'Siti Nurhaliza',
                    'date_of_birth' => '1995-12-05',
                    'gender' => 'female',
                    'phone' => '+62845678901',
                    'address' => 'Jl. Gatot Subroto No.25, Bandung',
                    'height' => 158,
                    'weight' => 52,
                    'blood_type' => 'AB+',
                    'wearable_type' => 'samsung',
                    'onboarding_completed' => true
                ]
            ],
            [
                'username' => 'budi_santoso',
                'email' => 'budi.santoso@email.com',
                'profile' => [
                    'full_name' => 'Budi Santoso',
                    'date_of_birth' => '1982-07-18',
                    'gender' => 'male',
                    'phone' => '+62856789012',
                    'address' => 'Jl. Diponegoro No.75, Surabaya',
                    'height' => 172,
                    'weight' => 85,
                    'blood_type' => 'O+',
                    'wearable_type' => 'none',
                    'onboarding_completed' => false
                ]
            ]
        ];

        $patientRole = Role::where('name', 'patient')->first();

        foreach ($patients as $patientData) {
            $user = User::create([
                'username' => $patientData['username'],
                'email' => $patientData['email'],
                'password' => Hash::make('password123'),
                'role' => 'patient',
                'organization_id' => $bioFarma->id,
                'status' => 'active',
                'email_verified_at' => now()
            ]);
            
            $user->roles()->attach($patientRole);

            PatientProfile::create(array_merge(
                ['user_id' => $user->id],
                $patientData['profile']
            ));

            // Assign clinicians to patients
            if (rand(0, 1)) {
                $user->patientProfile->assignedClinicians()->attach($clinician1->id);
            }
            if (rand(0, 1)) {
                $user->patientProfile->assignedClinicians()->attach($clinician2->id);
            }
        }

        $this->command->info('✅ Users and Patients seeded successfully!');
        $this->command->info('📧 Login credentials:');
        $this->command->info('   Super Admin: superadmin@medwell.id / password123');
        $this->command->info('   Admin: admin@biofarma.co.id / password123');
        $this->command->info('   Clinician: sarah.cardio@biofarma.co.id / password123');
        $this->command->info('   Patient: john.doe@email.com / password123');
    }
}
