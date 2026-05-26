<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Student;
use App\Models\Course;

try {
    // Check database connection
    DB::connection()->getPdo();
    echo "✓ Database connected successfully!\n\n";
    
    // Get all students
    $students = Student::select('id', 'fname', 'lname', 'email')->get();
    echo "STUDENTS (" . count($students) . "):\n";
    if (count($students) > 0) {
        foreach ($students as $s) {
            echo "  ID: {$s->id} | {$s->fname} {$s->lname} | {$s->email}\n";
        }
    } else {
        echo "  No students found.\n";
    }
    
    echo "\n";
    
    // Get all courses
    $courses = Course::select('id', 'course_name')->get();
    echo "COURSES (" . count($courses) . "):\n";
    if (count($courses) > 0) {
        foreach ($courses as $c) {
            echo "  ID: {$c->id} | {$c->course_name}\n";
        }
    } else {
        echo "  No courses found.\n";
    }
    
    echo "\n";
    
    // Get enrollment data
    $enrollments = DB::table('course__students')->select('course_id', 'student_id')->get();
    echo "ENROLLMENTS (" . count($enrollments) . "):\n";
    if (count($enrollments) > 0) {
        foreach ($enrollments as $e) {
            echo "  Student ID: {$e->student_id} -> Course ID: {$e->course_id}\n";
        }
    } else {
        echo "  No enrollments found.\n";
    }
    
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
?>
