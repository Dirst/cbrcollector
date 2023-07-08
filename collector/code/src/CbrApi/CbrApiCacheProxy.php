<?php

namespace Cbr\Collector\CbrApi;

use CentralBankRussian\ExchangeRate\Collections\CurrencyRateCollection;
use CentralBankRussian\ExchangeRate\ExchangeRate;

class CbrApiCacheProxy
{

    public function __construct(protected ExchangeRate $exchangeRate, protected \Redis $redis)
    {
    }

    public function getRateCollectionOnDate(\DateTimeInterface $dateTime): CurrencyRateCollection
    {
        $currencyRateCollection = $this->redis->get($this->getKeyFromDate($dateTime));
        if ($currencyRateCollection) {
            return unserialize($currencyRateCollection);
        }

        $currencyRateCollection = $this->exchangeRate
            ->setDate($dateTime)
            ->getCurrencyExchangeRates();

        $this->redis->set($this->getKeyFromDate($dateTime), serialize($currencyRateCollection));

        return $currencyRateCollection;
    }

    protected function getKeyFromDate(\DateTimeInterface $dateTime): string
    {
        return $dateTime->format('d.m.Y');
    }
}