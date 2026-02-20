<?php

namespace App\Console\Commands;

use App\Events\StudentUpdated;
use App\Services\KafkaProducerService;
use Illuminate\Console\Command;

class PublishStudentUpdatedEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'student:publish-updated {id=1001} {name=John Doe} {email=john@example.com} {fields=name,email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish a student updated event to Kafka';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $id = $this->argument('id');
        $name = $this->argument('name');
        $email = $this->argument('email');
        $fieldsStr = $this->argument('fields');
        $updatedFields = array_map('trim', explode(',', $fieldsStr));

        $this->info("╔════════════════════════════════════════════════════════════════╗");
        $this->info("║           Publishing StudentUpdated Event                     ║");
        $this->info("╚════════════════════════════════════════════════════════════════╝");
        $this->line("");
        $this->info("Event Details:");
        $this->info("  • Student ID: $id");
        $this->info("  • Name: $name");
        $this->info("  • Email: $email");
        $this->info("  • Updated Fields: " . implode(', ', $updatedFields));
        $this->line("");

        try {
            $event = new StudentUpdated($id, $name, $email, $updatedFields);
            $producer = new KafkaProducerService();
            $topic = env('KAFKA_TOPIC', 'student-events');

            $success = $producer->publish(
                $topic,
                "student-updated-$id",
                $event->toArray()
            );

            if ($success) {
                $this->info("✓ Event published successfully!");
                $this->info("  Topic: $topic");
                $this->info("  Message Key: student-updated-$id");
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
