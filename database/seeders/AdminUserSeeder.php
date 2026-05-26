<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserAccount;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $username = 'admin';
        $email = 'admin@example.com';
        $password = 'Admin1234!';

        $existing = UserAccount::where('username', $username)->orWhere('email', $email)->first();
        if ($existing) {
            $this->command->info('Admin user already exists. Skipping creation.');
            return;
        }

        UserAccount::create([
            'username' => $username,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'admin',
            'is_active' => 1,
        ]);

        $this->command->info("Admin user created: {$username} / {$email} (password: {$password})");
    }
}
