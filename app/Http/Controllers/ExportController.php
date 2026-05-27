<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Course;
use App\Models\Degree;
use App\Models\Post;
use App\Models\User;
use App\Services\ExportService;
use App\Services\ExcelExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Exception;

class ExportController extends Controller
{
    /**
     * Export students to PDF
     */
    public function exportStudentsPDF(Request $request)
    {
        try {
            $students = Student::with(['degree', 'userAccount'])->get();

            // Render view as HTML
            $html = View::make('admin.exports.students-pdf', [
                'students' => $students,
                'exportDate' => now()->format('Y-m-d H:i:s')
            ])->render();

            // Generate and download PDF
            ExportService::downloadPDF(
                $html,
                'students_report_' . now()->format('Y-m-d_H-i-s') . '.pdf',
                ['paper_size' => 'A4', 'orientation' => 'landscape']
            );

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to generate PDF: ' . $e->getMessage());
        }
    }

    /**
     * Export students to Excel (CSV/XLSX)
     */
    public function exportStudentsExcel(Request $request)
    {
        try {
            $students = Student::with(['degree'])->get();

            // Prepare data for export
            $data = $students->map(function ($student) {
                return [
                    'ID' => $student->id,
                    'First Name' => $student->fname,
                    'Middle Name' => $student->mname,
                    'Last Name' => $student->lname,
                    'Email' => $student->email,
                    'Contact No' => $student->contact_no ?? 'N/A',
                    'Age' => $student->age ?? 'N/A',
                    'Degree' => $student->degree?->title ?? 'N/A',
                ];
            })->toArray();

            // Export to Excel
            ExcelExportService::exportToExcel(
                $data,
                'students_report_' . now()->format('Y-m-d_H-i-s') . '.xlsx',
                array_keys(!empty($data) ? $data[0] : [])
            );

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to export Excel: ' . $e->getMessage());
        }
    }

    /**
     * Export degrees to PDF
     */
    public function exportDegreesPDF(Request $request)
    {
        try {
            $degrees = Degree::withCount('students')->get();

            $html = View::make('admin.exports.degrees-pdf', [
                'degrees' => $degrees,
                'exportDate' => now()->format('Y-m-d H:i:s')
            ])->render();

            ExportService::downloadPDF(
                $html,
                'degrees_report_' . now()->format('Y-m-d_H-i-s') . '.pdf'
            );

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to generate PDF: ' . $e->getMessage());
        }
    }

    /**
     * Export degrees to Excel
     */
    public function exportDegreesExcel(Request $request)
    {
        try {
            $degrees = Degree::withCount('students')->get();

            $data = $degrees->map(function ($degree) {
                return [
                    'ID' => $degree->id,
                    'Title' => $degree->title,
                    'Description' => $degree->description ?? 'N/A',
                    'Students Count' => $degree->students_count,
                ];
            })->toArray();

            ExcelExportService::exportToExcel(
                $data,
                'degrees_report_' . now()->format('Y-m-d_H-i-s') . '.xlsx',
                array_keys(!empty($data) ? $data[0] : [])
            );

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to export Excel: ' . $e->getMessage());
        }
    }

    /**
     * Export courses to PDF
     */
    public function exportCoursesPDF(Request $request)
    {
        try {
            $courses = Course::withCount('students')->get();

            $html = View::make('admin.exports.courses-pdf', [
                'courses' => $courses,
                'exportDate' => now()->format('Y-m-d H:i:s')
            ])->render();

            ExportService::downloadPDF(
                $html,
                'courses_report_' . now()->format('Y-m-d_H-i-s') . '.pdf'
            );

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to generate PDF: ' . $e->getMessage());
        }
    }

    /**
     * Export courses to Excel
     */
    public function exportCoursesExcel(Request $request)
    {
        try {
            $courses = Course::withCount('students')->get();

            $data = $courses->map(function ($course) {
                return [
                    'ID' => $course->id,
                    'Code' => $course->code ?? 'N/A',
                    'Title' => $course->title,
                    'Students Count' => $course->students_count,
                ];
            })->toArray();

            ExcelExportService::exportToExcel(
                $data,
                'courses_report_' . now()->format('Y-m-d_H-i-s') . '.xlsx',
                array_keys(!empty($data) ? $data[0] : [])
            );

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to export Excel: ' . $e->getMessage());
        }
    }

    /**
     * Export users to PDF
     */
    public function exportUsersPDF(Request $request)
    {
        try {
            $users = User::all();

            $html = View::make('admin.exports.users-pdf', [
                'users' => $users,
                'exportDate' => now()->format('Y-m-d H:i:s')
            ])->render();

            ExportService::downloadPDF(
                $html,
                'users_report_' . now()->format('Y-m-d_H-i-s') . '.pdf'
            );

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to generate PDF: ' . $e->getMessage());
        }
    }

    /**
     * Export users to Excel
     */
    public function exportUsersExcel(Request $request)
    {
        try {
            $users = User::all();

            $data = $users->map(function ($user) {
                return [
                    'ID' => $user->id,
                    'Name' => $user->name,
                    'Email' => $user->email,
                    'Role' => $user->role ?? 'user',
                    'Created At' => $user->created_at?->format('Y-m-d'),
                ];
            })->toArray();

            ExcelExportService::exportToExcel(
                $data,
                'users_report_' . now()->format('Y-m-d_H-i-s') . '.xlsx',
                array_keys(!empty($data) ? $data[0] : [])
            );

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to export Excel: ' . $e->getMessage());
        }
    }

    /**
     * Show export options page
     */
    public function index()
    {
        return view('admin.exports.index');
    }
}
