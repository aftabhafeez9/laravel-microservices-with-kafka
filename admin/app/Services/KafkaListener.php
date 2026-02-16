<?php

namespace App\Services;

use RdKafka\Consumer;

class KafkaListener
{
    protected Consumer $consumer;

    public function __construct(string $topicName, string $groupId = 'hello-world-group')
    {
        $conf = new \RdKafka\Conf();
        $conf->set('group.id', $groupId);
        $conf->set('auto.offset.reset', 'earliest');

        $this->consumer = new Consumer($conf);
        $this->consumer->addBrokers(env('KAFKA_BROKER', 'kafka:9092'));

        $topic = $this->consumer->newTopic($topicName);
        $topic->consumeStart(0, RD_KAFKA_OFFSET_END);
    }

    public function listen()
    {
        while (true) {
            $message = $this->consumer->consume(0, 1000);
            if ($message && $message->err === RD_KAFKA_RESP_ERR_NO_ERROR) {
                \Log::info("Received message: " . $message->payload);
                echo "Received message: " . $message->payload . PHP_EOL;
            }
        }
    }
}
