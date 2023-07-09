<?php

namespace Cbr\Sdk\CbrApi;

use CentralBankRussian\ExchangeRate\Collections\CurrencyCollection;
use CentralBankRussian\ExchangeRate\Collections\CurrencyRateCollection;
use CentralBankRussian\ExchangeRate\ExchangeRate;
use CentralBankRussian\ExchangeRate\ReferenceData;

class CbrApiCacheProxy
{

    public function __construct(protected ExchangeRate $exchangeRate, protected ReferenceData $reference, protected \Redis $redis)
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

    public function getCurrenciesList(): CurrencyCollection
    {
        $currencies = $this->redis->get('currencies');
        if ($currencies) {
            return unserialize($currencies);
        }

        $currencies = $this->reference->getCurrencyCodesDaily();

        $this->redis->set('currencies', serialize($currencies));

        return $currencies;
    }

    protected function getKeyFromDate(\DateTimeInterface $dateTime): string
    {
        return $dateTime->format('d.m.Y');
    }
}