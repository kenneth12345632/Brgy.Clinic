<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $services = [
        ['name' => 'Immunization', 'icon' => 'fa-syringe', 'color' => 'bg-blue-500', 'desc' => 'Essential vaccines for infants and children.'],
        ['name' => 'Family Planning', 'icon' => 'fa-house-chimney-medical', 'color' => 'bg-purple-500', 'desc' => 'Consultations and reproductive health services.'],
        ['name' => 'Deworming', 'icon' => 'fa-bug', 'color' => 'bg-emerald-500', 'desc' => 'Bi-annual deworming for school-aged children.'],
        ['name' => 'Pre-natal', 'icon' => 'fa-person-pregnant', 'color' => 'bg-rose-500', 'desc' => 'Regular check-ups for expectant mothers.'],
        ['name' => 'Ferrous', 'icon' => 'fa-capsules', 'color' => 'bg-slate-600', 'desc' => 'Iron supplementation for anemic patients.'],
        ['name' => 'Nutrition', 'icon' => 'fa-apple-whole', 'color' => 'bg-orange-500', 'desc' => 'Weight monitoring and feeding programs.'],
        ['name' => 'Dental', 'icon' => 'fa-tooth', 'color' => 'bg-cyan-500', 'desc' => 'Basic oral health check-ups and extractions.'],
        ['name' => 'Consultation', 'icon' => 'fa-user-doctor', 'color' => 'bg-indigo-500', 'desc' => 'General health assessment and referrals.'],
        ['name' => 'Laboratory', 'icon' => 'fa-microscope', 'color' => 'bg-teal-500', 'desc' => 'Basic blood and urine testing services.'],
        ['name' => 'Emergency', 'icon' => 'fa-kit-medical', 'color' => 'bg-red-500', 'desc' => 'First aid and immediate medical assistance.']
    ];

    foreach ($services as $s) {
        \App\Models\Service::create([
            'name' => $s['name'],
            'icon' => $s['icon'],
            'color' => $s['color'],
            'description' => $s['desc'],
            'schedule' => 'Monday - Friday, 8:00 AM - 5:00 PM'
        ]);
    }
}
}
