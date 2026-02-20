<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StudentCreated
{
    use Dispatchable, SerializesModels;

    public $studentId;
    public $studentName;
    public $studentEmail;
    public $timestamp;

    /**
     * Create a new event instance.
     */
    public function __construct($studentId, $studentName, $studentEmail)
    {
        $this->studentId = $studentId;
        $this->studentName = $studentName;
        $this->studentEmail = $studentEmail;
        $this->timestamp = now()->toDateTimeString();
    }

    /**
     * Convert event to array for Kafka serialization
     */
    public function toArray(): array
    {
        return [
            'event' => 'StudentCreated',
            'student_id' => $this->studentId,
            'student_name' => $this->studentName,
            'student_email' => $this->studentEmail,
            'timestamp' => $this->timestamp,
        ];
    }
}
