<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Student;
use App\Models\Course;

$rejie = Student::where('fname', 'Rejie')->first();
$marya = Student::where('fname', 'Marya')->first();
$e1 = Course::where('course_name', 'ELECTIVE 1')->first();
$e2 = Course::where('course_name', 'ELECTIVE 2')->first();

if ($rejie && $marya && $e1 && $e2) {
    $rejie->courses()->attach([$e1->id, $e2->id]);
    $marya->courses()->attach([$e1->id, $e2->id]);
    echo "✓ Rejie enrolled in ELECTIVE 1 and ELECTIVE 2\n";
    echo "✓ Marya enrolled in ELECTIVE 1 and ELECTIVE 2\n";
} else {
    echo "Error: Missing students or courses\n";
}
