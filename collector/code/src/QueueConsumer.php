<?php

namespace Cbr\Collector;

use Doctrine\ORM\EntityManagerInterface;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class QueueConsumer
{
    private const QUEUE_NAME = 'rates_collect';

    public function __construct(
        protected AMQPStreamConnection $queueConnection,
        protected RateCollector $rateCollector,
        protected EntityManagerInterface $entityManager
    ) {
    }

    public function consume(): void
    {
        $channel = $this->queueConnection->channel();
        $channel->queue_declare(self::QUEUE_NAME, false, true, false, false);

        $channel->basic_consume(
            self::QUEUE_NAME,
            '',
            false,
            false,
            false,
            false,
            function (AMQPMessage $message) {
                $this->collectRateStatForMessage($message);
            }
        );

        $channel->consume();
    }

    protected function collectRateStatForMessage(AMQPMessage $message): void
    {
        /* @var \Cbr\Sdk\Dto\RateCollectorQueueItem $queueItem */
        $queueItem = unserialize($message->getBody());

        $rateStatEntity = $this->rateCollector->getCurrencyRateStatEntity($queueItem);

        // @NOTICE can be extracted to interface in order to provide ways to save entity to different storages like files or else.
        $this->entityManager->persist($rateStatEntity);
        $this->entityManager->flush();

        $message->ack();
    }
}