<?php

namespace App\Entity;

use App\Repository\RealTimeOgrineRateRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RealTimeOgrineRateRepository::class)]
class RealTimeOgrineRate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $datetime = null;

    #[ORM\Column]
    private ?int $rate = null;

    #[ORM\Column]
    private ?int $numberOfOgrines = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatetime(): ?\DateTimeImmutable
    {
        return $this->datetime;
    }

    public function setDatetime(\DateTimeImmutable $datetime): static
    {
        $this->datetime = $datetime;

        return $this;
    }

    public function getRate(): ?int
    {
        return $this->rate;
    }

    public function setRate(int $rate): static
    {
        $this->rate = $rate;

        return $this;
    }

    public function getNumberOfOgrines(): ?int
    {
        return $this->numberOfOgrines;
    }

    public function setNumberOfOgrines(int $numberOfOgrines): static
    {
        $this->numberOfOgrines = $numberOfOgrines;

        return $this;
    }
}
