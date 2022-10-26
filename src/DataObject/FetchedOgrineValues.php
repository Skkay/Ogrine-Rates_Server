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
        $offset = 24 * 60 * 60; // Add 1 day to correct the fetched timestamps

        $this->currentRateTimestamp = $currentRateTimestamp + $offset;
        $this->currentRate = $currentRate;
        $this->previousRateTimestamp = $previousRateTimestamp + $offset;
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
