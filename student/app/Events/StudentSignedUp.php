<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StudentSignedUp
{
    use Dispatchable, SerializesModels;

    public $studentId;
    public $studentName;
    public $studentEmail;
    public $registrationNumber;
    public $department;
    public $timestamp;

    /**
     * Create a new event instance.
     */
    public function __construct($studentId, $studentName, $studentEmail, $registrationNumber, $department = null)
    {
        $this->studentId = $studentId;
        $this->studentName = $studentName;
        $this->studentEmail = $studentEmail;
        $this->registrationNumber = $registrationNumber;
        $this->department = $department;
        $this->timestamp = now()->toDateTimeString();
    }

    /**
     * Convert event to array for Kafka serialization
     */
    public function toArray(): array
    {
        return [
            'event' => 'StudentSignedUp',
            'student_id' => $this->studentId,
            'student_name' => $this->studentName,
            'student_email' => $this->studentEmail,
            'registration_number' => $this->registrationNumber,
            'department' => $this->department,
            'timestamp' => $this->timestamp,
        ];
    }
}
