<?php
namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Course;
use App\Models\Payment;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Create a new student.
     */
    public function createStudent(Request $request)
    {
        $request->validate([
            'fio' => 'required|string',
            'birthdate' => 'required|date',
            'contact' => 'required|string',
            'status' => 'required|in:active,graduated,suspended',
        ]);

        $student = Student::create($request->all());

        return response()->json([
            'message' => 'Student created',
            'student' => $student,
        ], 201);
    }

    /**
     * Get all students.
     */
    public function getStudents()
    {
        return response()->json(Student::all());
    }

    /**
     * Get a single student's details.
     */
    public function getStudent($student_id)
    {
        $student = Student::findOrFail($student_id);
        return response()->json($student);
    }

    /**
     * Update student's details.
     */
    public function updateStudent(Request $request, $student_id)
    {
        $student = Student::findOrFail($student_id);

        $request->validate([
            'fio' => 'required|string',
            'birthdate' => 'required|date',
            'contact' => 'required|string',
            'status' => 'required|in:active,graduated,suspended',
        ]);

        $student->update($request->all());

        return response()->json([
            'message' => 'Student updated',
            'student' => $student,
        ]);
    }

    /**
     * Delete a student.
     */
    public function deleteStudent($student_id)
    {
        $student = Student::findOrFail($student_id);
        $student->delete();

        return response()->json([
            'message' => 'Student deleted',
        ]);
    }

    /**
     * Get all courses enrolled by a student.
     */
    public function getStudentCourses($student_id)
    {
        $student = Student::findOrFail($student_id);
        return response()->json($student->courses);
    }

    /**
     * Enroll a student in a course.
     */
    public function enrollStudentInCourse(Request $request, $student_id)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'status' => 'required|in:enrolled,completed,dropped',
        ]);

        $student = Student::findOrFail($student_id);
        $course = Course::findOrFail($request->course_id);

        $student->courses()->attach($course, ['status' => $request->status]);

        return response()->json([
            'message' => 'Student enrolled in course',
        ]);
    }

    /**
     * Remove a course from a student's enrollment.
     */
    public function removeCourseFromStudent($student_id, $course_id)
    {
        $student = Student::findOrFail($student_id);
        $course = Course::findOrFail($course_id);

        $student->courses()->detach($course);

        return response()->json([
            'message' => 'Course removed from student',
        ]);
    }

    /**
     * Get all payments made by a student.
     */
    public function getStudentPayments($student_id)
    {
        $student = Student::findOrFail($student_id);
        return response()->json($student->payments);
    }

    /**
     * Add a payment for a student.
     */
    public function addPaymentForStudent(Request $request, $student_id)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'payment_date' => 'required|date',
        ]);

        $student = Student::findOrFail($student_id);

        $payment = $student->payments()->create([
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
        ]);

        return response()->json([
            'message' => 'Payment added for student',
            'payment' => $payment,
        ], 201);
    }

    /**
     * Delete a payment for a student.
     */
    public function deletePaymentForStudent($student_id, $payment_id)
    {
        $student = Student::findOrFail($student_id);
        $payment = $student->payments()->findOrFail($payment_id);

        $payment->delete();

        return response()->json([
            'message' => 'Payment deleted for student',
        ]);
    }
}
