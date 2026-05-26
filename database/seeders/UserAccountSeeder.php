<?php

namespace Database\Seeders;

use App\Models\UserAccount;
use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserAccountSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the user_accounts table.
     */
    public function run(): void
    {
        // Create admin user
        UserAccount::create([
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123', [
                'rounds' => 12,
            ]),
            'role' => 'admin',
            'is_active' => 1,
        ]);

        // Create student user
        $studentUser = UserAccount::create([
            'username' => 'student01',
            'email' => 'student@example.com',
            'password' => Hash::make('student123', [
                'rounds' => 12,
            ]),
            'role' => 'student',
            'is_active' => 1,
        ]);

        // Create corresponding Student record
        Student::create([
            'user_account_id' => $studentUser->id,
            'fname' => 'Juan',
            'mname' => 'D',
            'lname' => 'Student',
            'email' => 'student@example.com',
            'contact_no' => '09123456789',
            'degree_id' => 1,
        ]);

        // Create another test student for variety
        $studentUser2 = UserAccount::create([
            'username' => 'testuser',
            'email' => 'testuser@example.com',
            'password' => Hash::make('testpass123', [
                'rounds' => 12,
            ]),
            'role' => 'student',
            'is_active' => 1,
        ]);

        // Create corresponding Student record
        Student::create([
            'user_account_id' => $studentUser2->id,
            'fname' => 'Maria',
            'mname' => 'C',
            'lname' => 'Test',
            'email' => 'testuser@example.com',
            'contact_no' => '09213456789',
            'degree_id' => 1,
        ]);
    }
}
