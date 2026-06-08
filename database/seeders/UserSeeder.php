<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil data role
        $adminRole = Role::where('slug', 'admin')->first();
        $userRole = Role::where('slug', 'user')->first();

        // Seed Admin User
        $admin = User::updateOrCreate(
            ['whatsapp' => '081111111111'],
            [
                'name' => 'Admin Sawah Pulo Hub',
                'email' => 'admin@sawahpulohub.com',
                'password' => Hash::make('password'),
            ]
        );

        if ($adminRole && !$admin->roles()->where('slug', 'admin')->exists()) {
            $admin->roles()->attach($adminRole);
        }

        // Seed Visitor User
        $visitor = User::updateOrCreate(
            ['whatsapp' => '082222222222'],
            [
                'name' => 'Wisatawan Contoh',
                'email' => 'wisatawan@sawahpulohub.com',
                'password' => Hash::make('password'),
            ]
        );

        if ($userRole && !$visitor->roles()->where('slug', 'user')->exists()) {
            $visitor->roles()->attach($userRole);
        }
    }
}
