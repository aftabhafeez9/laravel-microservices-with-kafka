<?php

namespace App\Console\Commands;

use App\Events\StudentEnrolled;
use App\Services\KafkaProducerService;
use Illuminate\Console\Command;

class PublishStudentEnrolledEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'student:publish-enrolled {id=1001} {name=John Doe} {email=john@example.com} {courseId=C101} {courseName=Laravel Basics}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish a student enrolled event to Kafka';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $id = $this->argument('id');
        $name = $this->argument('name');
        $email = $this->argument('email');
        $courseId = $this->argument('courseId');
        $courseName = $this->argument('courseName');

        $this->info("╔════════════════════════════════════════════════════════════════╗");
        $this->info("║           Publishing StudentEnrolled Event                    ║");
        $this->info("╚════════════════════════════════════════════════════════════════╝");
        $this->line("");
        $this->info("Event Details:");
        $this->info("  • Student ID: $id");
        $this->info("  • Name: $name");
        $this->info("  • Email: $email");
        $this->info("  • Course ID: $courseId");
        $this->info("  • Course Name: $courseName");
        $this->line("");

        try {
            $event = new StudentEnrolled($id, $name, $email, $courseId, $courseName);
            $producer = new KafkaProducerService();
            $topic = env('KAFKA_TOPIC', 'student-events');

            $success = $producer->publish(
                $topic,
                "student-enrolled-$id-$courseId",
                $event->toArray()
            );

            if ($success) {
                $this->info("✓ Event published successfully!");
                $this->info("  Topic: $topic");
                $this->info("  Message Key: student-enrolled-$id-$courseId");
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
