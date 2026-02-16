<?php

namespace App\Services;

use RdKafka\Producer;

class KafkaProducer
{
    protected Producer $producer;

    public function __construct()
    {
        $conf = new \RdKafka\Conf();
        $this->producer = new Producer($conf);
        $this->producer->addBrokers(env('KAFKA_BROKER', 'kafka:9092'));
    }

    public function publish(string $topicName, string $message)
    {
        $topic = $this->producer->newTopic($topicName);
        $topic->produce(RD_KAFKA_PARTITION_UA, 0, $message);
        $this->producer->flush(10000);
    }
}
