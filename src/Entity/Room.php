<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\DBAL\Types\Types;

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
        unique: true,
        nullable: false
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
        type: Types::DECIMAL,
        precision: 5,
        scale: 2,
        nullable: false
    )]
    #[Assert\NotBlank]
    private ?string $price = null;

    #[ORM\OneToMany(
        mappedBy: 'room',
        targetEntity: OvernightStay::class,
        orphanRemoval: true
    )]
    private Collection $overnightStays;

    public function __construct()
    {
        $this->overnightStays = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getNumber(): ?int
    {
        return $this->number;
    }

    /**
     * @param int $number
     * @return $this
     */
    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getNumberOfBeds(): ?int
    {
        return $this->numberOfBeds;
    }

    /**
     * @param int $numberOfBeds
     * @return $this
     */
    public function setNumberOfBeds(int $numberOfBeds): self
    {
        $this->numberOfBeds = $numberOfBeds;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return $this
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPrice(): ?string
    {
        return $this->price;
    }

    /**
     * @param string $price
     * @return $this
     */
    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection<int, OvernightStay>
     */
    public function getOvernightStays(): Collection
    {
        return $this->overnightStays;
    }

    /**
     * @param OvernightStay $overnightStay
     * @return $this
     */
    public function addOvernightStay(OvernightStay $overnightStay): self
    {
        if (!$this->overnightStays->contains($overnightStay)) {
            $this->overnightStays->add($overnightStay);
            $overnightStay->setRoom($this);
        }

        return $this;
    }

    /**
     * @param OvernightStay $overnightStay
     * @return $this
     */
    public function removeOvernightStay(OvernightStay $overnightStay): self
    {
        if ($this->overnightStays->removeElement($overnightStay)) {
            // set the owning side to null (unless already changed)
            if ($overnightStay->getRoom() === $this) {
                $overnightStay->setRoom(null);
            }
        }

        return $this;
    }
}
