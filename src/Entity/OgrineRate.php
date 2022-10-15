<?php

namespace App\Entity;

use App\Repository\OgrineRateRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OgrineRateRepository::class)]
class OgrineRate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $datetime = null;

    #[ORM\Column]
    private ?int $rateTenth = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatetime(): ?\DateTimeInterface
    {
        return $this->datetime;
    }

    public function setDatetime(\DateTimeInterface $datetime): self
    {
        $this->datetime = $datetime;

        return $this;
    }

    public function getRateTenth(): ?int
    {
        return $this->rateTenth;
    }

    public function setRateTenth(int $rateTenth): self
    {
        $this->rateTenth = $rateTenth;

        return $this;
    }

    public function getRate(): ?float
    {
        return $this->rateTenth / 10;
    }
}
