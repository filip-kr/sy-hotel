<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: 'email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
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
    #[Assert\NotBlank]
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
    #[Assert\Email(
        message: 'Neispravan e-mail',
    )]
    #[Assert\Length(
        min: 5,
        max: 30
    )]
    private ?string $email = null;

    #[ORM\Column(name: 'roles')]
    private array $roles = [];

    /**
     * @var string|null The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(
        name: 'is_verified',
        type: 'boolean',
        nullable: true
    )]
    private ?bool $isVerified = false;

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
     * @param string|null $firstName
     * @return $this
     */
    public function setFirstName(?string $firstName): self
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
     * @param string|null $lastName
     * @return $this
     */
    public function setLastName(?string $lastName): self
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
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param array $roles
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return bool
     */
    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    /**
     * @param bool|null $isVerified
     * @return $this
     */
    public function setIsVerified(?bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
    }
}
