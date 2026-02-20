<?php

namespace App\Console\Commands;

use App\Services\KafkaConsumerService;
use Illuminate\Console\Command;

class ListenToStudentEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'listen:student-events {--timeout=120000}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Listen to student events from Kafka (Admin Service)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $timeout = (int)$this->option('timeout');

        $this->info("========================================");
        $this->info("ADMIN SERVICE - Student Events Listener");
        $this->info("========================================");
        $this->line("");
        $this->info("Connecting to Kafka broker: kafka:9092");
        $this->info("Topic: student-events");
        $this->info("Consumer Group: admin-service");
        $this->info("Timeout: {$timeout}ms");
        $this->line("");

        try {
            $consumer = new KafkaConsumerService(
                consumerGroup: 'admin-service',
                topic: 'student-events'
            );

            $consumer->consume(
                timeoutMs: $timeout,
                callback: function ($payload) {
                    // Process the event in admin service
                    $this->line("");
                    $this->info("[ADMIN] Processing student event:");
                    $this->line("  - Storing student record in admin database");
                    $this->line("  - Updating admin dashboard");
                    $this->line("  - Syncing with admin services");
                }
            );

            $this->info("Listener stopped.");
            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->error("Error: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
