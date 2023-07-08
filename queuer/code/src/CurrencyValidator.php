<?php

namespace Cbr\Queuer;

class CurrencyValidator
{
    /**
     * @NOTICE this is always returns true and must be implemented in real life task.
     */
    public function isCurrencyCodeValid(string $currency): bool
    {
        return true;
    }
}