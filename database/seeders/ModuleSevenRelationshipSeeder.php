<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Post;
use App\Models\Profile;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class ModuleSevenRelationshipSeeder extends Seeder
{
    /**
     * Seed Module 7 sample data for relationships.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Module Seven User One',
                'email' => 'module7.user1@example.com',
                'password' => 'password',
            ],
            [
                'name' => 'Module Seven User Two',
                'email' => 'module7.user2@example.com',
                'password' => 'password',
            ],
        ];

        $createdUsers = [];
        foreach ($users as $userData) {
            $user = User::query()->updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => $userData['password'],
                ]
            );
            $createdUsers[] = $user;
        }

        foreach ($createdUsers as $index => $user) {
            Profile::query()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'bio' => 'This is sample profile bio #' . ($index + 1),
                    'avatar' => 'avatars/default-' . ($index + 1) . '.png',
                ]
            );

            Post::query()->updateOrCreate(
                [
                    'user_id' => $user->id,
                    'title' => 'Welcome Post ' . ($index + 1),
                ],
                ['content' => 'Sample post content for Module 7 relationship demo.']
            );
        }

        $courses = [
            ['title' => 'Web Systems and Technologies 2', 'description' => 'Elective 1 module sample course'],
            ['title' => 'Database Management Systems', 'description' => 'Core course sample'],
            ['title' => 'Object-Oriented Programming', 'description' => 'Core programming course'],
        ];

        foreach ($courses as $courseData) {
            Course::query()->updateOrCreate(
                ['title' => $courseData['title']],
                ['description' => $courseData['description']]
            );
        }

        $allCourseIds = Course::query()->pluck('id')->all();

        if (!empty($allCourseIds)) {
            $students = Student::query()->take(5)->get();

            foreach ($students as $student) {
                // Sync keeps seed reruns idempotent while preserving pivot uniqueness.
                $student->courses()->sync($allCourseIds);
            }
        }
    }
}
