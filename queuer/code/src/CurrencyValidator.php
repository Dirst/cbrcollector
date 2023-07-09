<?php

namespace Cbr\Queuer;

use Cbr\Sdk\CbrApi\CbrApiCacheProxy;

class CurrencyValidator
{
    public function __construct(protected CbrApiCacheProxy $apiCacheProxy)
    {
    }

    /**
     * @NOTICE this is always returns true and must be implemented in real life task.
     */
    public function isCurrencyCodeValid(string $currency): bool
    {
        $currency = $this->apiCacheProxy->getCurrenciesList()->getCurrencyBySymbolCode(strtoupper($currency));

        if ($currency) {
            return true;
        }

        return false;
    }
}