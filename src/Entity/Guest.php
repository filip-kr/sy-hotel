<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\GuestRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: GuestRepository::class)]
#[UniqueEntity(
    fields: 'email',
    message: 'E-mail adresa već postoji'
)]
#[UniqueEntity(
    fields: 'oib',
    message: 'OIB već postoji'
)]
#[UniqueEntity(
    fields: 'passport_number',
    message: 'Broj putovnice već postoji'
)]
class Guest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(
        name: 'first_name',
        type: 'string',
        nullable: false
    )]
    #[Assert\NotBlank]
    private ?string $firstName;

    #[ORM\Column(
        name: 'last_name',
        type: 'string',
        nullable: false
    )]
    #[Assert\NotBlank]
    private ?string $lastName;

    #[ORM\Column(
        name: 'email',
        type: 'string',
        unique: true,
        nullable: false
    )]
    #[Assert\NotBlank]
    #[Assert\Email(
        message: 'Neispravan e-mail',
    )]
    private ?string $email = null;

    #[ORM\Column(
        name: 'dob',
        type: Types::DATETIME_MUTABLE,
        nullable: false
    )]
    #[Assert\NotBlank]
    private ?\DateTimeInterface $dob = null;

    #[ORM\Column(
        name: 'country',
        type: 'string',
        nullable: false
    )]
    #[Assert\NotBlank]
    private ?string $country = null;

    #[ORM\Column(
        name: 'oib',
        type: 'string',
        length: 11,
        options: ['fixed' => true],
        nullable: true
    )]
    private ?string $oib = null;

    #[ORM\Column(
        name: 'passport_number',
        type: 'string',
        nullable: true
    )]
    private ?string $passportNumber = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getDob(): ?\DateTimeInterface
    {
        return $this->dob;
    }

    public function setDob(\DateTimeInterface $dob): self
    {
        $this->dob = $dob;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getOib(): ?string
    {
        return $this->oib;
    }

    public function setOib(?string $oib): self
    {
        $this->oib = $oib;

        return $this;
    }

    public function getPassportNumber(): ?string
    {
        return $this->passportNumber;
    }

    public function setPassportNumber(?string $passportNumber): self
    {
        $this->passportNumber = $passportNumber;

        return $this;
    }
}
