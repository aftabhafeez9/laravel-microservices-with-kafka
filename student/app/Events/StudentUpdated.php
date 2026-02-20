<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StudentUpdated
{
    use Dispatchable, SerializesModels;

    public $studentId;
    public $studentName;
    public $studentEmail;
    public $updatedFields;
    public $timestamp;

    /**
     * Create a new event instance.
     */
    public function __construct($studentId, $studentName, $studentEmail, array $updatedFields = [])
    {
        $this->studentId = $studentId;
        $this->studentName = $studentName;
        $this->studentEmail = $studentEmail;
        $this->updatedFields = $updatedFields;
        $this->timestamp = now()->toDateTimeString();
    }

    /**
     * Convert event to array for Kafka serialization
     */
    public function toArray(): array
    {
        return [
            'event' => 'StudentUpdated',
            'student_id' => $this->studentId,
            'student_name' => $this->studentName,
            'student_email' => $this->studentEmail,
            'updated_fields' => $this->updatedFields,
            'timestamp' => $this->timestamp,
        ];
    }
}
