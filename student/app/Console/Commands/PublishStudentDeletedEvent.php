<?php

namespace App\Console\Commands;

use App\Events\StudentDeleted;
use App\Services\KafkaProducerService;
use Illuminate\Console\Command;

class PublishStudentDeletedEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'student:publish-deleted {id=1001} {name=John Doe} {email=john@example.com}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish a student deleted event to Kafka';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $id = $this->argument('id');
        $name = $this->argument('name');
        $email = $this->argument('email');

        $this->info("╔════════════════════════════════════════════════════════════════╗");
        $this->info("║           Publishing StudentDeleted Event                     ║");
        $this->info("╚════════════════════════════════════════════════════════════════╝");
        $this->line("");
        $this->info("Event Details:");
        $this->info("  • Student ID: $id");
        $this->info("  • Name: $name");
        $this->info("  • Email: $email");
        $this->line("");
        $this->warn("⚠️  Account Deletion - This action is irreversible");
        $this->line("");

        try {
            $event = new StudentDeleted($id, $name, $email);
            $producer = new KafkaProducerService();
            $topic = env('KAFKA_TOPIC', 'student-events');

            $success = $producer->publish(
                $topic,
                "student-deleted-$id",
                $event->toArray()
            );

            if ($success) {
                $this->info("✓ Event published successfully!");
                $this->info("  Topic: $topic");
                $this->info("  Message Key: student-deleted-$id");
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
