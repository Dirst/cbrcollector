<?php

namespace Cbr\Queuer\Queue;

use Cbr\Sdk\Dto\CurrencyPair;
use Cbr\Sdk\Dto\RateCollectorQueueItem;

class QueueItemsBuilder
{
    public function buildQueueItems(int $daysCount, CurrencyPair $currencyPair): \Generator
    {
        for ($i = 1; $i <= $daysCount; $i++) {
            $date = new \DateTime("today -$i days");

            yield new RateCollectorQueueItem($date, $currencyPair);
        }
    }
}