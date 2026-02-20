<?php

namespace App\Http\Controllers;

use App\Events\StudentSignedUp;
use App\Models\Student;
use App\Services\KafkaProducerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class StudentController extends Controller
{
    /**
     * Register a new student
     */
    public function signup(Request $request): JsonResponse
    {
        try {
            // Validate request
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:students,email',
                'phone' => 'nullable|string|max:20',
                'registration_number' => 'required|string|unique:students,registration_number',
                'department' => 'nullable|string|max:255',
            ]);

            // Create student
            $student = Student::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'] ?? null,
                'registration_number' => $validated['registration_number'],
                'department' => $validated['department'] ?? null,
                'status' => 'active',
            ]);

            // Dispatch StudentSignedUp event and publish to Kafka
            $this->publishSignupEvent($student);

            return response()->json([
                'success' => true,
                'message' => 'Student registered successfully',
                'data' => [
                    'id' => $student->id,
                    'name' => $student->name,
                    'email' => $student->email,
                    'registration_number' => $student->registration_number,
                ],
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error during registration: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get student profile
     */
    public function show($id): JsonResponse
    {
        try {
            $student = Student::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $student,
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found',
            ], 404);
        }
    }

    /**
     * Get all students
     */
    public function index(): JsonResponse
    {
        try {
            $students = Student::paginate(15);

            $student = Student::create([
                'name' => 'test1',
                'email' => 'tes11t@test.com',
                'phone' => '1231213123',
                'registration_number' => '1231123123123123123',
                'department' => '1231231231231231123123212312',
                'status' => 'active',
            ]);

            
            $this->publishSignupEvent($student);
            dd('21231');

            
            return response()->json([
                'success' => true,
                'data' => $students,
            ], 200);




        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e.'Error fetching students',
            ], 500);
        }
    }

    /**
     * Update student
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $student = Student::findOrFail($id);

            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|unique:students,email,' . $id,
                'phone' => 'nullable|string|max:20',
                'department' => 'nullable|string|max:255',
                'status' => 'sometimes|in:active,inactive,suspended',
            ]);

            $student->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Student updated successfully',
                'data' => $student,
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating student',
            ], 500);
        }
    }

    /**
     * Delete student
     */
    public function destroy($id): JsonResponse
    {
        try {
            $student = Student::findOrFail($id);
            $student->delete();

            return response()->json([
                'success' => true,
                'message' => 'Student deleted successfully',
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting student',
            ], 500);
        }
    }

    /**
     * Publish StudentSignedUp event to Kafka
     */
    private function publishSignupEvent(Student $student): void
    {
        try {
            $event = new StudentSignedUp(
                $student->id,
                $student->name,
                $student->email,
                $student->registration_number,
                $student->department
            );

            $producer = new KafkaProducerService();
            $topic = env('KAFKA_TOPIC', 'student-events');

            $producer->publish(
                $topic,
                "student-signup-" . $student->id,
                $event->toArray()
            );

        } catch (\Throwable $e) {
            // Log error but don't fail the registration
            \Log::error('Error publishing StudentSignedUp event: ' . $e->getMessage());
        }
    }
}
