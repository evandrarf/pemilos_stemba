<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@pemilos.com',
            'email_verified_at' => now(),
            'password' => Hash::make('rahasia123')
        ]);
        $admin->assignRole('admin');

        $user = User::create([
            'name' => 'User',
            'email' => 'user@pemilos.com',
            'email_verified_at' => now(),
            'password' => Hash::make('rahasia123')
        ]);

        $user->assignRole('user');
    }
}
