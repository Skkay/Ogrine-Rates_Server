<?php

namespace App\Entity;

use App\Repository\DiscordWebhookRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DiscordWebhookRepository::class)]
#[ORM\HasLifecycleCallbacks]
class DiscordWebhook
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $url = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 255)]
    private ?string $messageLocale = null;

    #[ORM\Column(nullable: true)]
    private ?int $lastResponseStatus = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $lastResponse = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $datetimeLastSuccessfulResponse = null;

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getMessageLocale(): ?string
    {
        return $this->messageLocale;
    }

    public function setMessageLocale(string $messageLocale): self
    {
        $this->messageLocale = $messageLocale;

        return $this;
    }

    public function getLastResponseStatus(): ?int
    {
        return $this->lastResponseStatus;
    }

    public function setLastResponseStatus(?int $lastResponseStatus): self
    {
        $this->lastResponseStatus = $lastResponseStatus;

        return $this;
    }

    public function getLastResponse(): ?string
    {
        return $this->lastResponse;
    }

    public function setLastResponse(?string $lastResponse): self
    {
        $this->lastResponse = $lastResponse;

        return $this;
    }

    public function getDatetimeLastSuccessfulResponse(): ?\DateTimeInterface
    {
        return $this->datetimeLastSuccessfulResponse;
    }

    public function setDatetimeLastSuccessfulResponse(?\DateTimeInterface $datetimeLastSuccessfulResponse): self
    {
        $this->datetimeLastSuccessfulResponse = $datetimeLastSuccessfulResponse;

        return $this;
    }
}
