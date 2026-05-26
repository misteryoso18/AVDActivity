<?php

namespace Tests\Feature;

use App\Models\UserAccount;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordChangeFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_and_student_change_password_only_once(): void
    {
        $roles = [
            'teacher' => 'teacher.dashboard',
            'student' => 'student.dashboard',
        ];

        foreach ($roles as $role => $dashboardRoute) {
            $initialPassword = 'InitialPass123!';
            $updatedPassword = 'UpdatedPass123!';

            $user = UserAccount::create([
                'username' => $role . '_user',
                'email' => $role . '@example.com',
                'password' => Hash::make($initialPassword),
                'role' => $role,
                'is_active' => 1,
                'must_change_password' => true,
            ]);

            $this->post(route('authenticate'), [
                'username' => $user->username,
                'password' => $initialPassword,
            ])->assertRedirect(route('password.change'));

            $this->withSession([
                'user_id' => $user->id,
                'username' => $user->username,
                'role' => $role,
                'must_change_password' => true,
            ])->post(route('password.update'), [
                'current_password' => $initialPassword,
                'new_password' => $updatedPassword,
                'confirm_password' => $updatedPassword,
            ])->assertRedirect(route($dashboardRoute));

            $this->get(route('logout'));

            $this->post(route('authenticate'), [
                'username' => $user->username,
                'password' => $updatedPassword,
            ])->assertRedirect(route($dashboardRoute));

            $this->get(route('password.change'))
                ->assertRedirect(route($dashboardRoute));
        }
    }

    public function test_mixed_case_teacher_and_student_roles_do_not_loop_back_to_password_change(): void
    {
        $roles = [
            'Teacher' => 'teacher.dashboard',
            'Student' => 'student.dashboard',
        ];

        foreach ($roles as $role => $dashboardRoute) {
            $initialPassword = 'InitialPass123!';
            $updatedPassword = 'UpdatedPass123!';

            $user = UserAccount::create([
                'username' => strtolower($role) . '_user',
                'email' => strtolower($role) . '@example.com',
                'password' => Hash::make($initialPassword),
                'role' => $role,
                'is_active' => 1,
                'must_change_password' => true,
            ]);

            $this->post(route('authenticate'), [
                'username' => $user->username,
                'password' => $initialPassword,
            ])->assertRedirect(route('password.change'));

            $this->withSession([
                'user_id' => $user->id,
                'username' => $user->username,
                'role' => strtolower($role),
                'must_change_password' => true,
            ])->post(route('password.update'), [
                'current_password' => $initialPassword,
                'new_password' => $updatedPassword,
                'confirm_password' => $updatedPassword,
            ])->assertRedirect(route($dashboardRoute));

            $this->get(route('logout'));

            $this->post(route('authenticate'), [
                'username' => $user->username,
                'password' => $updatedPassword,
            ])->assertRedirect(route($dashboardRoute));
        }
    }
}