<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organization;
use App\Models\Facility;
use App\Models\Department;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        // Bio Farma - Main Organization
        $bioFarma = Organization::create([
            'name' => 'Bio Farma',
            'code' => 'BF001',
            'type' => 'other',
            'email' => 'info@biofarma.co.id',
            'phone' => '+62-22-2033755',
            'address' => 'Jl. Pasteur No.28, Pasteur, Sukajadi, Bandung',
            'city' => 'Bandung',
            'state' => 'Jawa Barat',
            'postal_code' => '40161',
            'country' => 'Indonesia',
            'website' => 'https://www.biofarma.co.id',
            'status' => 'active'
        ]);

        // Facilities under Bio Farma
        $hq = Facility::create([
            'organization_id' => $bioFarma->id,
            'name' => 'Bio Farma Headquarters',
            'type' => 'corporate_office',
            'address' => 'Jl. Pasteur No.28, Bandung',
            'phone' => '+62-22-2033755',
            'status' => 'active'
        ]);

        $clinic = Facility::create([
            'organization_id' => $bioFarma->id,
            'name' => 'Bio Farma Health Center',
            'type' => 'clinic',
            'address' => 'Jl. Pasteur No.30, Bandung',
            'phone' => '+62-22-2034000',
            'status' => 'active'
        ]);

        // Departments in HQ
        Department::create([
            'facility_id' => $hq->id,
            'name' => 'Information Technology',
            'code' => 'IT',
            'status' => 'active'
        ]);

        Department::create([
            'facility_id' => $hq->id,
            'name' => 'Human Resources',
            'code' => 'HR',
            'status' => 'active'
        ]);

        // Departments in Clinic
        Department::create([
            'facility_id' => $clinic->id,
            'name' => 'General Practice',
            'code' => 'GP',
            'status' => 'active'
        ]);

        Department::create([
            'facility_id' => $clinic->id,
            'name' => 'Internal Medicine',
            'code' => 'IM',
            'status' => 'active'
        ]);

        Department::create([
            'facility_id' => $clinic->id,
            'name' => 'Cardiology',
            'code' => 'CARDIO',
            'status' => 'active'
        ]);

        // Sample Partner Organization
        $partner = Organization::create([
            'name' => 'Rumah Sakit Umum Jakarta',
            'code' => 'RSU001',
            'type' => 'hospital',
            'email' => 'info@rsujakarta.co.id',
            'phone' => '+62-21-5551234',
            'address' => 'Jl. Sudirman No.100, Jakarta',
            'city' => 'Jakarta',
            'state' => 'DKI Jakarta',
            'postal_code' => '12190',
            'country' => 'Indonesia',
            'website' => 'https://www.rsujakarta.co.id',
            'status' => 'active'
        ]);

        $partnerFacility = Facility::create([
            'organization_id' => $partner->id,
            'name' => 'RSU Jakarta - Main Building',
            'type' => 'hospital',
            'address' => 'Jl. Sudirman No.100, Jakarta',
            'phone' => '+62-21-5551234',
            'status' => 'active'
        ]);

        Department::create([
            'facility_id' => $partnerFacility->id,
            'name' => 'Emergency',
            'code' => 'ER',
            'status' => 'active'
        ]);

        $this->command->info('✅ Organizations, Facilities, and Departments seeded!');
    }
}
