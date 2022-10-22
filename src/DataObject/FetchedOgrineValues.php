<?php

namespace App\DataObject;

class FetchedOgrineValues
{
    private $currentRateTimestamp;
    private $currentRate;
    private $previousRateTimestamp;
    private $previousRate;

    public function __construct(int $currentRateTimestamp, float $currentRate, int $previousRateTimestamp, float $previousRate)
    {
        $this->currentRateTimestamp = $currentRateTimestamp;
        $this->currentRate = $currentRate;
        $this->previousRateTimestamp = $previousRateTimestamp;
        $this->previousRate = $previousRate;
    }

    public function getCurrentRateTimestamp(): int
    {
        return $this->currentRateTimestamp;
    }

    public function getCurrentRate(): float
    {
        return $this->currentRate;
    }

    public function getPreviousRateTimestamp(): int
    {
        return $this->previousRateTimestamp;
    }

    public function getPreviousRate(): float
    {
        return $this->previousRate;
    }
}
