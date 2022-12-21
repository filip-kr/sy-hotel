<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\OvernightStayRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OvernightStayRepository::class)]
class OvernightStay
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'overnightStays')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Room $room = null;

    #[ORM\Column(
        name: 'total_price',
        type: Types::DECIMAL,
        precision: 5,
        scale: 2,
        nullable: false
    )]
    #[Assert\NotBlank]
    private ?string $totalPrice = null;

    #[ORM\Column(
        name: 'is_active',
        type: 'boolean',
        nullable: true
    )]
    private ?bool $isActive = null;

    #[ORM\OneToOne(
        mappedBy: 'overnightStay',
        cascade: ['persist', 'remove']
    )]
    #[Assert\NotBlank]
    private ?Reservation $reservation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): self
    {
        $this->room = $room;

        return $this;
    }

    public function getTotalPrice(): ?string
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(?string $totalPrice): self
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getReservation(): ?Reservation
    {
        return $this->reservation;
    }

    public function setReservation(?Reservation $reservation): self
    {
        // unset the owning side of the relation if necessary
        if ($reservation === null && $this->reservation !== null) {
            $this->reservation->setOvernightStay(null);
        }

        // set the owning side of the relation if necessary
        if ($reservation !== null && $reservation->getOvernightStay() !== $this) {
            $reservation->setOvernightStay($this);
        }

        $this->reservation = $reservation;

        return $this;
    }
}
