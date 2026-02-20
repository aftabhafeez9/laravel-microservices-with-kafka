<?php

namespace App\Services;

use Throwable;

class KafkaProducerService
{
    private $producer;
    private $topic;

    public function __construct()
    {
        try {
            $conf = new \RdKafka\Conf();
            $conf->set('bootstrap.servers', env('KAFKA_BROKERS', 'kafka:9092'));
            $conf->setErrorCb(function ($kafka, $err, $reason) {
                echo "Err $err: $reason\n";
            });
            $conf->setDrMsgCb(function ($kafka, $message) {
                // Delivery report callback
            });

            $this->producer = new \RdKafka\Producer($conf);
        } catch (Throwable $e) {
            echo "Error initializing Kafka Producer: " . $e->getMessage() . "\n";
        }
    }

    /**
     * Publish event to Kafka topic
     */
    public function publish(string $topic, string $key, array $message): bool
    {
        try {
            $topic = $this->producer->newTopic($topic);
            $topic->produce(
                RD_KAFKA_PARTITION_UA,
                0,
                json_encode($message),
                $key
            );

            // Flush pending messages
            $this->producer->flush(10000);

            return true;
        } catch (Throwable $e) {
            echo "Error publishing message: " . $e->getMessage() . "\n";
            return false;
        }
    }

    public function close(): void
    {
        if ($this->producer) {
            $this->producer->flush(10000);
        }
    }

    public function __destruct()
    {
        $this->close();
    }
}
