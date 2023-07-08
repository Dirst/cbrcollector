<?php

namespace Cbr\Queuer\Queue;

use Cbr\Sdk\Dto\CurrencyPair;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class CollectRateTaskQueuer
{
    private const QUEUE_NAME = 'rates_collect';

    public function __construct(
        protected AMQPStreamConnection $streamConnection,
        protected QueueItemsBuilder $itemsBuilder
    ) {
    }

    public function buildUpQueue(int $daysCount, CurrencyPair $currencyPair): void
    {
        $channel = $this->streamConnection->channel();
        $channel->queue_declare(
            self::QUEUE_NAME,
            false,
            true,
            false,
            false
        );

        foreach ($this->itemsBuilder->buildQueueItems($daysCount, $currencyPair) as $item) {
            $channel->basic_publish(
                new AMQPMessage(
                    serialize($item), ['delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT]
                ),
                '',
                self::QUEUE_NAME
            );
        }
    }

}