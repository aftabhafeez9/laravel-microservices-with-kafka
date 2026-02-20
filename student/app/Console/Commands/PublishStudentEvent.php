<?php

namespace App\Console\Commands;

use App\Events\StudentCreated;
use App\Services\KafkaProducerService;
use Illuminate\Console\Command;

class PublishStudentEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'student:publish-event {name=John Doe} {email=john@example.com}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish a student created event to Kafka';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $email = $this->argument('email');
        $studentId = rand(1000, 9999);

        $this->info("Publishing StudentCreated event...");
        $this->info("Student ID: $studentId");
        $this->info("Name: $name");
        $this->info("Email: $email");

        try {
            // Create the event
            $event = new StudentCreated($studentId, $name, $email);

            // Publish to Kafka
            $producer = new KafkaProducerService();
            $topic = env('KAFKA_TOPIC', 'student-events');
            
            $success = $producer->publish(
                $topic,
                "student-$studentId",
                $event->toArray()
            );

            if ($success) {
                $this->info("✓ Event published successfully to Kafka topic: $topic");
                return Command::SUCCESS;
            } else {
                $this->error("✗ Failed to publish event");
                return Command::FAILURE;
            }
        } catch (\Throwable $e) {
            $this->error("✗ Error: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
