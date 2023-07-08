<?php

namespace Cbr\Collector\Entity;

use Ramsey\Uuid\Doctrine\UuidGenerator;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

#[ORM\Entity]
#[ORM\Table(name: "rate_stat")]
class RateStatEntity
{

    #[ORM\Id]
    #[ORM\Column(type: 'uuid_binary', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    protected UuidInterface|string $id;

    public function getId(): string
    {
        return $this->id;
    }

    #[ORM\Column(type: 'decimal', precision: 16, scale: 3, nullable: false)]
    public float $rate;

    #[ORM\Column(type: 'decimal', precision: 16, scale: 3, nullable: false)]
    public float $rateDayChange;

    #[ORM\Column(type: "string", length: 5, nullable: false)]
    public string $currency;

    #[ORM\Column(type: "string", length: 5, nullable: true)]
    public ?string $baseCurrency = null;

    #[ORM\Column(type: 'date')]
    public \DateTimeInterface $date;
}