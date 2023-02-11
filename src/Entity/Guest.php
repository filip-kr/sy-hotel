<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\GuestRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Oib as CustomAssert;

#[ORM\Entity(repositoryClass: GuestRepository::class)]
#[UniqueEntity(fields: 'email')]
#[UniqueEntity(fields: 'oib')]
#[UniqueEntity(fields: 'passportNumber')]
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
    #[Assert\Length(
        min: 2,
        max: 50
    )]
    private ?string $firstName;

    #[ORM\Column(
        name: 'last_name',
        type: 'string',
        nullable: false
    )]
    #[Assert\Length(
        min: 2,
        max: 50
    )]
    private ?string $lastName;

    #[ORM\Column(
        name: 'email',
        type: 'string',
        unique: true,
        nullable: false
    )]
    #[Assert\NotBlank]
    #[Assert\Email]
    #[Assert\Length(
        min: 5,
        max: 30
    )]
    private ?string $email = null;

    #[ORM\Column(
        name: 'dob',
        type: Types::DATETIME_MUTABLE,
        nullable: false
    )]
    #[Assert\NotBlank]
    private ?DateTimeInterface $dob = null;

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
        nullable: true,
        options: ['fixed' => true]
    )]
    #[Assert\Expression(
        "this.getCountry() != 'HR' or this.getCountry() == 'HR' and this.getOib() != null ? true : false"
    )]
    #[CustomAssert\Oib]
    private ?string $oib = null;

    #[ORM\Column(
        name: 'passport_number',
        type: 'string',
        nullable: true
    )]
    #[Assert\Expression(
        "this.getCountry() == 'HR' or this.getCountry() != 'HR' and this.getPassportNumber() != null ? true : false"
    )]
    #[Assert\Length(
        max: 50
    )]
    private ?string $passportNumber = null;

    #[ORM\OneToMany(
        mappedBy: 'guest',
        targetEntity: Reservation::class,
        orphanRemoval: true
    )]
    private Collection $reservations;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return $this
     */
    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return $this
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getDob(): ?DateTimeInterface
    {
        return $this->dob;
    }

    /**
     * @param DateTimeInterface $dob
     * @return $this
     */
    public function setDob(DateTimeInterface $dob): self
    {
        $this->dob = $dob;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return $this
     */
    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getOib(): ?string
    {
        return $this->oib;
    }

    /**
     * @param string|null $oib
     * @return $this
     */
    public function setOib(?string $oib): self
    {
        $this->oib = $oib;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassportNumber(): ?string
    {
        return $this->passportNumber;
    }

    /**
     * @param string|null $passportNumber
     * @return $this
     */
    public function setPassportNumber(?string $passportNumber): self
    {
        $this->passportNumber = $passportNumber;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    /**
     * @param Reservation $reservation
     * @return $this
     */
    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setGuest($this);
        }

        return $this;
    }

    /**
     * @param Reservation $reservation
     * @return $this
     */
    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getGuest() === $this) {
                $reservation->setGuest(null);
            }
        }

        return $this;
    }
}
