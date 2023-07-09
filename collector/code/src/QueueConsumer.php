<?php

namespace Cbr\Collector;

use Cbr\Collector\Entity\RateStatEntity;
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
                $message->ack();
            }
        );

        $channel->consume();
    }

    protected function collectRateStatForMessage(AMQPMessage $message): void
    {
        /* @var \Cbr\Sdk\Dto\RateCollectorQueueItem $queueItem */
        $queueItem = unserialize($message->getBody());

        $rateStatEntity = $this->entityManager->getRepository(RateStatEntity::class)->findOneBy([
            'currency' => $queueItem->currencyPair->currencyCode,
            'baseCurrency' => $queueItem->currencyPair->baseCurrencyCode,
            'date' => $queueItem->dateTime,
        ]);

        if ($rateStatEntity) {
            return;
        }

        $rateStatEntity = $this->rateCollector->getCurrencyRateStatEntity($queueItem);

        // @NOTICE can be extracted to interface in order to provide ways to save entity to different storages like files or else.
        $this->entityManager->persist($rateStatEntity);
        $this->entityManager->flush();
    }
}