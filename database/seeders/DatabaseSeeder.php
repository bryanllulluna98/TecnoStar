<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Administrador por defecto para TecnoStar
        User::updateOrCreate(
            ['email' => 'admin@tecnostar.com'],
            [
                'name' => 'Administrador TecnoStar',
                'password' => Hash::make('admin1234'),
            ]
        );

        $this->call([
            ServiceSeeder::class,
            MessageTemplateSeeder::class,
        ]);
    }
}
