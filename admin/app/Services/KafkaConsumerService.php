<?php

namespace App\Services;

use Throwable;

class KafkaConsumerService
{
    private $consumer;
    private $topic;

    public function __construct(string $consumerGroup = 'consumer-group', string $topic = 'student-events')
    {
        try {
            $conf = new \RdKafka\Conf();
            $conf->set('bootstrap.servers', env('KAFKA_BROKERS', 'kafka:9092'));
            $conf->set('group.id', $consumerGroup);
            $conf->set('auto.offset.reset', 'earliest');
            $conf->set('enable.auto.commit', 'true');
            $conf->set('api.version.request.timeout.ms', 10000);

            $conf->setErrorCb(function ($kafka, $err, $reason) {
                echo "Err $err: $reason\n";
            });

            $this->consumer = new \RdKafka\KafkaConsumer($conf);
            $this->consumer->subscribe([$topic]);
        } catch (Throwable $e) {
            echo "Error initializing Kafka Consumer: " . $e->getMessage() . "\n";
        }
    }

    /**
     * Consume messages from Kafka topic
     */
    public function consume(int $timeoutMs = 120000, callable $callback = null): void
    {
        try {
            $startTime = time();
            $timeout = $timeoutMs / 1000;

            $this->printInfo("Listening for messages... (timeout: {$timeout}s)");

            while (true) {
                $message = $this->consumer->consume($timeoutMs);

                if ($message === null) {
                    // Timeout reached
                    if ((time() - $startTime) >= $timeout) {
                        $this->printInfo("No messages received. Timeout reached.");
                        break;
                    }
                    continue;
                }

                switch ($message->err) {
                    case RD_KAFKA_RESP_ERR_NO_ERROR:
                        $payload = json_decode($message->payload, true);
                        
                        $this->printSuccess("\n✓ Message received:");
                        $this->printMessage($payload);

                        if (is_callable($callback)) {
                            $callback($payload);
                        }

                        $startTime = time(); // Reset timeout
                        break;

                    case RD_KAFKA_RESP_ERR__PARTITION_EOF:
                        $this->printInfo("Waiting for messages...");
                        break;

                    case RD_KAFKA_RESP_ERR__TIMED_OUT:
                        break;

                    default:
                        throw new \Exception($message->errstr(), $message->err);
                }
            }
        } catch (Throwable $e) {
            $this->printError("Error consuming messages: " . $e->getMessage());
        }
    }

    private function printMessage(array $data): void
    {
        echo "  Event: " . ($data['event'] ?? 'Unknown') . "\n";
        echo "  Student ID: " . ($data['student_id'] ?? 'N/A') . "\n";
        echo "  Name: " . ($data['student_name'] ?? 'N/A') . "\n";
        echo "  Email: " . ($data['student_email'] ?? 'N/A') . "\n";
        echo "  Timestamp: " . ($data['timestamp'] ?? 'N/A') . "\n";
    }

    private function printInfo(string $message): void
    {
        echo "\033[36m" . $message . "\033[0m\n";
    }

    private function printSuccess(string $message): void
    {
        echo "\033[32m" . $message . "\033[0m\n";
    }

    private function printError(string $message): void
    {
        echo "\033[31m" . $message . "\033[0m\n";
    }

    public function close(): void
    {
        if ($this->consumer) {
            $this->consumer->unsubscribe();
            $this->consumer->close();
        }
    }

    public function __destruct()
    {
        $this->close();
    }
}
