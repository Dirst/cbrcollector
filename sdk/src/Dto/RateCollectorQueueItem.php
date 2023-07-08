<?php

namespace Cbr\Sdk\Dto;

class RateCollectorQueueItem
{
    public function __construct(
        public \DateTimeInterface $dateTime,
        public CurrencyPair $currencyPair
    ) {

    }
}