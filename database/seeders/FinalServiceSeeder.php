<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FinalServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $services = [
        ['name' => 'Immunization', 'icon' => 'fa-syringe', 'color' => 'bg-blue-600'],
        ['name' => 'Family Planning', 'icon' => 'fa-house-user', 'color' => 'bg-purple-600'],
        ['name' => 'Deworming', 'icon' => 'fa-bug', 'color' => 'bg-green-600'],
        ['name' => 'Supplementation', 'icon' => 'fa-pills', 'color' => 'bg-orange-600'],
        ['name' => 'Pre-natal', 'icon' => 'fa-baby', 'color' => 'bg-rose-600'],
        ['name' => 'Ferrous', 'icon' => 'fa-capsules', 'color' => 'bg-slate-700'],
        ['name' => 'Free Consultation', 'icon' => 'fa-user-doctor', 'color' => 'bg-indigo-600'],
        ['name' => 'RBS (Random Blood Sugar)', 'icon' => 'fa-droplet', 'color' => 'bg-red-600'],
        ['name' => 'Feeding Program', 'icon' => 'fa-bowl-food', 'color' => 'bg-lime-600'],
        ['name' => 'TB DOTS', 'icon' => 'fa-lungs', 'color' => 'bg-red-700']
    ];

    foreach ($services as $s) {
        \App\Models\Service::updateOrCreate(
            ['name' => $s['name']], // Prevents duplicates
            [
                'icon' => $s['icon'],
                'color' => $s['color'],
                'description' => 'Essential ' . $s['name'] . ' services.',
                'schedule' => 'Monday - Friday, 8:00 AM - 5:00 PM'
            ]
        );
    }
}
}
