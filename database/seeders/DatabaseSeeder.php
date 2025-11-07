<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting Medwell Database Seeding...');
        $this->command->newLine();

        // 1. Roles & Permissions
        $this->command->info('📋 Seeding Roles & Permissions...');
        $this->call(RoleSeeder::class);
        $this->command->newLine();

        // 2. Organizations, Facilities, Departments
        $this->command->info('🏢 Seeding Organizations & Facilities...');
        $this->call(OrganizationSeeder::class);
        $this->command->newLine();

        // 3. Users & Patients
        $this->command->info('👥 Seeding Users & Patients...');
        $this->call(UserSeeder::class);
        $this->command->newLine();

        // 4. Medications
        $this->command->info('💊 Seeding Medications...');
        $this->call(MedicationSeeder::class);
        $this->command->newLine();

        $this->command->info('✅ Database seeding completed successfully!');
        $this->command->newLine();
        $this->command->line('═══════════════════════════════════════════════════');
        $this->command->info('🔐 DEFAULT LOGIN CREDENTIALS:');
        $this->command->line('═══════════════════════════════════════════════════');
        $this->command->line('Super Admin:');
        $this->command->line('  Email: superadmin@medwell.id');
        $this->command->line('  Password: password123');
        $this->command->newLine();
        $this->command->line('Admin:');
        $this->command->line('  Email: admin@biofarma.co.id');
        $this->command->line('  Password: password123');
        $this->command->newLine();
        $this->command->line('Clinician:');
        $this->command->line('  Email: sarah.cardio@biofarma.co.id');
        $this->command->line('  Password: password123');
        $this->command->newLine();
        $this->command->line('Patient:');
        $this->command->line('  Email: john.doe@email.com');
        $this->command->line('  Password: password123');
        $this->command->line('═══════════════════════════════════════════════════');
    }
}
