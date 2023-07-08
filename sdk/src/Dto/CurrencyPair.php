<?php

namespace Cbr\Sdk\Dto;

class CurrencyPair
{
    public function __construct(public string $currencyCode, public ?string $baseCurrencyCode = null)
    {
    }
}