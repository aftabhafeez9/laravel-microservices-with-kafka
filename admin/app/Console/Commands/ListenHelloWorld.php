<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ListenHelloWorld extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:listen-hello-world';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $conf = new \RdKafka\Conf();

        $conf->set('group.id', 'admin-group');
        $conf->set('metadata.broker.list', env('KAFKA_BROKER'));
        $conf->set('auto.offset.reset', 'earliest');

        $consumer = new \RdKafka\KafkaConsumer($conf);

        $consumer->subscribe(['hello-topic']);

        $this->info("Listening...");

        while (true) {
            $message = $consumer->consume(120*1000);

            switch ($message->err) {
                case RD_KAFKA_RESP_ERR_NO_ERROR:
                    $this->info("Received: " . $message->payload);
                    break;

                case RD_KAFKA_RESP_ERR__PARTITION_EOF:
                    break;

                case RD_KAFKA_RESP_ERR__TIMED_OUT:
                    break;

                default:
                    throw new \Exception($message->errstr(), $message->err);
            }
        }
    }

}
