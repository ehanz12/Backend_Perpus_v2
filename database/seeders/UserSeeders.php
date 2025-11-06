<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $user = User::create([
            'firstname' => 'Admin',
            'lastname' => 'Utama',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
        ]);
        $latest = $user->id;
        Role::create(['role' => 'admin', 'user_id' => $latest]);



    }
}
