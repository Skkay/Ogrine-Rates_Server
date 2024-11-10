<?php

namespace App\DataObject;

class FetchedRealTimeOgrineValue
{
    private \DateTimeImmutable $fetchedAt;

    public function __construct(
        private int $currentRate,
        private int $numberOfOgrines,
    ) {
        $this->fetchedAt = new \DateTimeImmutable();
    }

    public function getFetchedAt(): \DateTimeImmutable
    {
        return $this->fetchedAt;
    }

    public function getCurrentRate(): int
    {
        return $this->currentRate;
    }

    public function getNumberOfOgrines(): int
    {
        return $this->numberOfOgrines;
    }
}
