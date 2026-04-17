<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@asistencia.local',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Usuario Demo',
            'email' => 'demo@asistencia.local',
            'password' => Hash::make('password'),
        ]);
    }
}
