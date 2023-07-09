<?php

namespace Cbr\Collector;

use Cbr\Sdk\CbrApi\CbrApiCacheProxy;
use Cbr\Sdk\Dto\CurrencyPair;
use Cbr\Sdk\Dto\RateCollectorQueueItem;
use Cbr\Collector\Entity\RateStatEntity;

class RateCollector
{
    public function __construct(protected CbrApiCacheProxy $api)
    {
    }

    public function getCurrencyRateStatEntity(RateCollectorQueueItem $queueItem): RateStatEntity
    {
        $rate = $this->getRateOnDateForCrossPair($queueItem->dateTime, $queueItem->currencyPair);
        $yesterdayRate = $this->getRateOnDateForCrossPair(
            new \DateTime($queueItem->dateTime->format('d.m.Y').' -1 day'),
            $queueItem->currencyPair
        );

        $rateStat = new RateStatEntity();
        $rateStat->currency = $queueItem->currencyPair->currencyCode;
        $rateStat->baseCurrency = $queueItem->currencyPair->baseCurrencyCode;
        $rateStat->rate = $rate;
        $rateStat->rateDayChange = $rate - $yesterdayRate;
        $rateStat->date = $queueItem->dateTime;

        return $rateStat;
    }

    protected function getRateOnDateForCrossPair(\DateTimeInterface $date, CurrencyPair $currencyPair): float
    {
        $currencyRateCollection = $this->api->getRateCollectionOnDate($date);
        $rate = $currencyRateCollection->getCurrencyRateBySymbolCode(strtoupper($currencyPair->currencyCode))->getExchangeRate();
        if ($currencyPair->baseCurrencyCode) {
            $rate /= $currencyRateCollection->getCurrencyRateBySymbolCode(strtoupper($currencyPair->baseCurrencyCode))->getExchangeRate();
        }

        return $rate;
    }
}