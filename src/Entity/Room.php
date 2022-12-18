<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: RoomRepository::class)]
#[UniqueEntity(fields: 'number')]
class Room
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(
        name: 'number',
        type: 'integer',
        nullable: false,
        unique: true
    )]
    #[Assert\NotBlank]
    #[Assert\Length(
        max: 3
    )]
    private ?int $number = null;

    #[ORM\Column(
        name: 'number_of_beds',
        type: 'integer',
        nullable: false
    )]
    #[Assert\NotBlank]
    #[Assert\Length(
        max: 2
    )]
    private ?int $numberOfBeds = null;

    #[ORM\Column(
        name: 'description',
        type: 'string',
        nullable: true
    )]
    #[Assert\Length(
        min: 2,
        max: 50
    )]
    private ?string $description = null;

    #[ORM\Column(
        name: 'price',
        type: 'integer',
        nullable: false
    )]
    #[Assert\Length(
        max: 10
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
