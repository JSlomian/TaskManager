<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Entity;

use App\Infrastructure\Doctrine\Repository\EventEntityRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventEntityRepository::class)]
#[ORM\HasLifecycleCallbacks]
class EventEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $aggregateType = null;

    #[ORM\Column]
    private ?int $aggregateId = null;

    #[ORM\Column(length: 100)]
    private ?string $eventType = null;

    #[ORM\Column]
    private array $payload = [];

    #[ORM\Column]
    private ?\DateTimeImmutable $occurredAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAggregateType(): ?string
    {
        return $this->aggregateType;
    }

    public function setAggregateType(string $aggregateType): static
    {
        $this->aggregateType = $aggregateType;

        return $this;
    }

    public function getAggregateId(): ?int
    {
        return $this->aggregateId;
    }

    public function setAggregateId(int $aggregateId): static
    {
        $this->aggregateId = $aggregateId;

        return $this;
    }

    public function getEventType(): ?string
    {
        return $this->eventType;
    }

    public function setEventType(string $eventType): static
    {
        $this->eventType = $eventType;

        return $this;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }

    public function getPayloadJson(): string
    {
        return json_encode($this->payload, JSON_THROW_ON_ERROR);
    }

    public function setPayload(array $payload): static
    {
        $this->payload = $payload;

        return $this;
    }

    public function getOccurredAt(): ?\DateTimeImmutable
    {
        return $this->occurredAt;
    }

    #[ORM\PrePersist]
    public function setOccurredAt(): void
    {
        $this->occurredAt = new \DateTimeImmutable();
    }
}
