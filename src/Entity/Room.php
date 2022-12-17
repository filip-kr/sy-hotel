<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RoomRepository::class)]
class Room
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(
        name: 'number',
        type: 'integer',
        nullable: false
    )]
    #[Assert\NotBlank]
    private ?int $number = null;

    #[ORM\Column(
        name: 'number_of_beds',
        type: 'integer',
        nullable: false
    )]
    #[Assert\NotBlank]
    private ?int $numberOfBeds = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(
        name: 'price',
        type: 'integer',
        nullable: false
    )]
    private ?int $price = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getNumberOfBeds(): ?int
    {
        return $this->numberOfBeds;
    }

    public function setNumberOfBeds(int $numberOfBeds): self
    {
        $this->numberOfBeds = $numberOfBeds;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }
}
