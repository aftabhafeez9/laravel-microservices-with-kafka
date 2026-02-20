<?php

namespace App\Console\Commands;

use App\Services\KafkaConsumerService;
use App\Services\NotificationEventHandler;
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
    protected $description = 'Listen to student events from Kafka (Notification Service)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $timeout = (int)$this->option('timeout');

        $this->info("╔════════════════════════════════════════════════════════════════╗");
        $this->info("║       NOTIFICATION SERVICE - Student Events Listener           ║");
        $this->info("╚════════════════════════════════════════════════════════════════╝");
        $this->line("");
        $this->info("Configuration:");
        $this->info("  • Kafka Broker: kafka:9092");
        $this->info("  • Topic: student-events");
        $this->info("  • Consumer Group: notification-service");
        $this->info("  • Timeout: {$timeout}ms (" . ($timeout / 1000) . "s)");
        $this->line("");
        $this->info("Listening for events:");
        $this->info("  ✓ StudentSignedUp");
        $this->info("  ✓ UserLoggedIn");
        $this->info("  ✓ StudentCreated");
        $this->info("  ✓ StudentUpdated");
        $this->info("  ✓ StudentDeleted");
        $this->info("  ✓ StudentEnrolled");
        $this->line("");

        try {
            $consumer = new KafkaConsumerService(
                consumerGroup: 'notification-service',
                topic: 'student-events'
            );

            $eventCount = 0;

            $consumer->consume(
                timeoutMs: $timeout,
                callback: function ($payload) use (&$eventCount) {
                    $eventCount++;
                    $eventType = $payload['event'] ?? 'Unknown';

                    // Route to appropriate handler
                    match ($eventType) {
                        'StudentSignedUp' => NotificationEventHandler::handleStudentSignedUp($payload),
                        'UserLoggedIn' => NotificationEventHandler::handleUserLoggedIn($payload),
                        'StudentCreated' => NotificationEventHandler::handleStudentCreated($payload),
                        'StudentUpdated' => NotificationEventHandler::handleStudentUpdated($payload),
                        'StudentDeleted' => NotificationEventHandler::handleStudentDeleted($payload),
                        'StudentEnrolled' => NotificationEventHandler::handleStudentEnrolled($payload),
                        default => $this->handleUnknownEvent($payload),
                    };
                }
            );

            $this->line("");
            $this->info("╔════════════════════════════════════════════════════════════════╗");
            $this->info("║                      Listener Stopped                         ║");
            $this->info("║                  Total Events Processed: $eventCount                  ║");
            $this->info("╚════════════════════════════════════════════════════════════════╝");

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->error("✗ Error: " . $e->getMessage());
            return Command::FAILURE;
        }
    }

    private function handleUnknownEvent(array $payload): void
    {
        $this->error("\n✗ Unknown event type: " . ($payload['event'] ?? 'Unknown'));
        echo "  Event payload: " . json_encode($payload, JSON_PRETTY_PRINT) . "\n";
    }
}
