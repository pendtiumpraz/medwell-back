<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Medication;
use App\Models\Organization;

class MedicationSeeder extends Seeder
{
    public function run(): void
    {
        $bioFarma = Organization::where('name', 'Bio Farma')->first();

        $medications = [
            // Cardiovascular Medications
            [
                'name' => 'Amlodipine',
                'generic_name' => 'Amlodipine Besylate',
                'brand_name' => 'Norvasc',
                'category' => 'Cardiovascular',
                'description' => 'Calcium channel blocker used to treat high blood pressure and chest pain',
                'dosage_forms' => json_encode(['tablet']),
                'strengths' => json_encode(['5mg', '10mg']),
                'route' => 'oral',
                'organization_id' => $bioFarma->id,
                'requires_prescription' => true,
                'status' => 'active'
            ],
            [
                'name' => 'Atorvastatin',
                'generic_name' => 'Atorvastatin Calcium',
                'brand_name' => 'Lipitor',
                'category' => 'Cardiovascular',
                'description' => 'Statin medication to lower cholesterol and triglycerides',
                'dosage_forms' => json_encode(['tablet']),
                'strengths' => json_encode(['10mg', '20mg', '40mg']),
                'route' => 'oral',
                'organization_id' => $bioFarma->id,
                'requires_prescription' => true,
                'status' => 'active'
            ],
            [
                'name' => 'Metoprolol',
                'generic_name' => 'Metoprolol Succinate',
                'brand_name' => 'Toprol-XL',
                'category' => 'Cardiovascular',
                'description' => 'Beta blocker for high blood pressure and heart conditions',
                'dosage_forms' => json_encode(['tablet']),
                'strengths' => json_encode(['25mg', '50mg', '100mg']),
                'route' => 'oral',
                'organization_id' => $bioFarma->id,
                'requires_prescription' => true,
                'status' => 'active'
            ],

            // Diabetes Medications
            [
                'name' => 'Metformin',
                'generic_name' => 'Metformin Hydrochloride',
                'brand_name' => 'Glucophage',
                'category' => 'Antidiabetic',
                'description' => 'First-line medication for type 2 diabetes',
                'dosage_forms' => json_encode(['tablet', 'extended-release']),
                'strengths' => json_encode(['500mg', '850mg', '1000mg']),
                'route' => 'oral',
                'organization_id' => $bioFarma->id,
                'requires_prescription' => true,
                'status' => 'active'
            ],
            [
                'name' => 'Insulin Glargine',
                'generic_name' => 'Insulin Glargine',
                'brand_name' => 'Lantus',
                'category' => 'Antidiabetic',
                'description' => 'Long-acting insulin for blood sugar control',
                'dosage_forms' => json_encode(['injection']),
                'strengths' => json_encode(['100 units/mL']),
                'route' => 'subcutaneous',
                'organization_id' => $bioFarma->id,
                'requires_prescription' => true,
                'status' => 'active'
            ],

            // Blood Pressure Medications
            [
                'name' => 'Lisinopril',
                'generic_name' => 'Lisinopril',
                'brand_name' => 'Prinivil',
                'category' => 'Cardiovascular',
                'description' => 'ACE inhibitor for high blood pressure',
                'dosage_forms' => json_encode(['tablet']),
                'strengths' => json_encode(['5mg', '10mg', '20mg']),
                'route' => 'oral',
                'organization_id' => $bioFarma->id,
                'requires_prescription' => true,
                'status' => 'active'
            ],
            [
                'name' => 'Losartan',
                'generic_name' => 'Losartan Potassium',
                'brand_name' => 'Cozaar',
                'category' => 'Cardiovascular',
                'description' => 'ARB medication for high blood pressure',
                'dosage_forms' => json_encode(['tablet']),
                'strengths' => json_encode(['25mg', '50mg', '100mg']),
                'route' => 'oral',
                'organization_id' => $bioFarma->id,
                'requires_prescription' => true,
                'status' => 'active'
            ],

            // Pain & Anti-inflammatory
            [
                'name' => 'Aspirin',
                'generic_name' => 'Acetylsalicylic Acid',
                'brand_name' => 'Bayer Aspirin',
                'category' => 'Pain Relief',
                'description' => 'NSAID for pain relief and cardiovascular protection',
                'dosage_forms' => json_encode(['tablet']),
                'strengths' => json_encode(['81mg', '325mg']),
                'route' => 'oral',
                'organization_id' => $bioFarma->id,
                'requires_prescription' => false,
                'status' => 'active'
            ],
            [
                'name' => 'Ibuprofen',
                'generic_name' => 'Ibuprofen',
                'brand_name' => 'Advil',
                'category' => 'Pain Relief',
                'description' => 'NSAID for pain, fever, and inflammation',
                'dosage_forms' => json_encode(['tablet', 'capsule']),
                'strengths' => json_encode(['200mg', '400mg', '600mg']),
                'route' => 'oral',
                'organization_id' => $bioFarma->id,
                'requires_prescription' => false,
                'status' => 'active'
            ],

            // Respiratory
            [
                'name' => 'Omeprazole',
                'generic_name' => 'Omeprazole',
                'brand_name' => 'Prilosec',
                'category' => 'Gastrointestinal',
                'description' => 'Proton pump inhibitor for acid reflux',
                'dosage_forms' => json_encode(['capsule']),
                'strengths' => json_encode(['20mg', '40mg']),
                'route' => 'oral',
                'organization_id' => $bioFarma->id,
                'requires_prescription' => false,
                'status' => 'active'
            ],

            // Supplements
            [
                'name' => 'Vitamin D3',
                'generic_name' => 'Cholecalciferol',
                'brand_name' => 'Vitamin D3',
                'category' => 'Supplement',
                'description' => 'Essential vitamin for bone health',
                'dosage_forms' => json_encode(['tablet', 'capsule']),
                'strengths' => json_encode(['1000IU', '2000IU', '5000IU']),
                'route' => 'oral',
                'organization_id' => $bioFarma->id,
                'requires_prescription' => false,
                'status' => 'active'
            ],
            [
                'name' => 'Omega-3 Fish Oil',
                'generic_name' => 'Omega-3 Fatty Acids',
                'brand_name' => 'Fish Oil',
                'category' => 'Supplement',
                'description' => 'Supplement for heart and brain health',
                'dosage_forms' => json_encode(['capsule', 'softgel']),
                'strengths' => json_encode(['1000mg', '1200mg']),
                'route' => 'oral',
                'organization_id' => $bioFarma->id,
                'requires_prescription' => false,
                'status' => 'active'
            ]
        ];

        foreach ($medications as $med) {
            Medication::create($med);
        }

        $this->command->info('✅ Medications seeded successfully!');
    }
}
