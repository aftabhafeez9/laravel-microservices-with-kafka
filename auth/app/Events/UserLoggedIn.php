<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserLoggedIn
{
    use Dispatchable, SerializesModels;

    public $userId;
    public $userName;
    public $userEmail;
    public $loginTime;
    public $ipAddress;
    public $userAgent;
    public $timestamp;

    /**
     * Create a new event instance.
     */
    public function __construct($userId, $userName, $userEmail, $ipAddress = null, $userAgent = null)
    {
        $this->userId = $userId;
        $this->userName = $userName;
        $this->userEmail = $userEmail;
        $this->loginTime = now()->toDateTimeString();
        $this->ipAddress = $ipAddress;
        $this->userAgent = $userAgent;
        $this->timestamp = now()->toDateTimeString();
    }

    /**
     * Convert event to array for Kafka serialization
     */
    public function toArray(): array
    {
        return [
            'event' => 'UserLoggedIn',
            'user_id' => $this->userId,
            'user_name' => $this->userName,
            'user_email' => $this->userEmail,
            'login_time' => $this->loginTime,
            'ip_address' => $this->ipAddress,
            'user_agent' => $this->userAgent,
            'timestamp' => $this->timestamp,
        ];
    }
}
