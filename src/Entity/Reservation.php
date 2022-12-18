<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    private ?Guest $guest = null;

    #[ORM\OneToOne(
        inversedBy: 'reservation',
        cascade: ['persist', 'remove']
    )]
    private ?OvernightStay $overnightStay = null;

    #[ORM\Column(
        name: 'sign_in_date',
        type: Types::DATETIME_MUTABLE,
        nullable: false
    )]
    #[Assert\NotBlank]
    private ?\DateTimeInterface $signInDate = null;

    #[ORM\Column(
        name: 'sign_out_date',
        type: Types::DATETIME_MUTABLE,
        nullable: true
    )]
    private ?\DateTimeInterface $signOutDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGuest(): ?Guest
    {
        return $this->guest;
    }

    public function setGuest(?Guest $guest): self
    {
        $this->guest = $guest;

        return $this;
    }

    public function getOvernightStay(): ?OvernightStay
    {
        return $this->overnightStay;
    }

    public function setOvernightStay(?OvernightStay $overnightStay): self
    {
        $this->overnightStay = $overnightStay;

        return $this;
    }

    public function getSignInDate(): ?\DateTimeInterface
    {
        return $this->signInDate;
    }

    public function setSignInDate(\DateTimeInterface $signInDate): self
    {
        $this->signInDate = $signInDate;

        return $this;
    }

    public function getSignOutDate(): ?\DateTimeInterface
    {
        return $this->signOutDate;
    }

    public function setSignOutDate(?\DateTimeInterface $signOutDate): self
    {
        $this->signOutDate = $signOutDate;

        return $this;
    }
}
