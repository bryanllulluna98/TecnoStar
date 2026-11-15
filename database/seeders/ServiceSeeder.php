<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name' => 'Netflix',
                'type' => 'profile',
                'suggested_price' => 5.00,
                'default_profiles' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Disney+ / Star+',
                'type' => 'profile',
                'suggested_price' => 5.00,
                'default_profiles' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'Max (HBO)',
                'type' => 'profile',
                'suggested_price' => 5.00,
                'default_profiles' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Prime Video',
                'type' => 'profile',
                'suggested_price' => 5.00,
                'default_profiles' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'Spotify',
                'type' => 'full_account',
                'suggested_price' => 3.50,
                'default_profiles' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Magis TV',
                'type' => 'full_account',
                'suggested_price' => 9.00,
                'default_profiles' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'YouTube Premium',
                'type' => 'full_account',
                'suggested_price' => 3.50,
                'default_profiles' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Paramount+',
                'type' => 'full_account',
                'suggested_price' => 3.50,
                'default_profiles' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'En Vivo Play',
                'type' => 'full_account',
                'suggested_price' => 2.50,
                'default_profiles' => 1,
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(['name' => $service['name']], $service);
        }
    }
}
