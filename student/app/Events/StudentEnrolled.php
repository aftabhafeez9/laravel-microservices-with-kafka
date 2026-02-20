<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StudentEnrolled
{
    use Dispatchable, SerializesModels;

    public $studentId;
    public $studentName;
    public $studentEmail;
    public $courseId;
    public $courseName;
    public $timestamp;

    /**
     * Create a new event instance.
     */
    public function __construct($studentId, $studentName, $studentEmail, $courseId, $courseName)
    {
        $this->studentId = $studentId;
        $this->studentName = $studentName;
        $this->studentEmail = $studentEmail;
        $this->courseId = $courseId;
        $this->courseName = $courseName;
        $this->timestamp = now()->toDateTimeString();
    }

    /**
     * Convert event to array for Kafka serialization
     */
    public function toArray(): array
    {
        return [
            'event' => 'StudentEnrolled',
            'student_id' => $this->studentId,
            'student_name' => $this->studentName,
            'student_email' => $this->studentEmail,
            'course_id' => $this->courseId,
            'course_name' => $this->courseName,
            'timestamp' => $this->timestamp,
        ];
    }
}
