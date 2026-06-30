<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        // Único usuario del sistema: Administrador (RH y Jefe de Sistemas)
        $admin = User::create([
            'name' => 'Administrador del Sistema',
            'email' => 'admin@baglass.com',
            'password' => Hash::make('Admin123!'),
            'phone' => '(55) 1234-5678',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('Administrador');
    }
}
