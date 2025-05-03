<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'Il existe déjà un compte avec cette adresse email.')]
/**
 * Représente un utilisateur de l'application.
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * Identifiant unique de l'utilisateur.
     *
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Adresse email de l'utilisateur.
     *
     * @var string|null
     */
    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * Rôles attribués à l'utilisateur (ex. ROLE_USER, ROLE_ADMIN).
     *
     * @var list<string>
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * Mot de passe haché de l'utilisateur.
     *
     * @var string|null
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * Nom d'utilisateur (identifiant visuel).
     *
     * @var string|null
     */
    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;

    /**
     * Adresse de livraison de l'utilisateur.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private ?string $deliveryAddress = null;

    /**
     * Statut de vérification du compte.
     *
     * @var bool
     */
    #[ORM\Column]
    private bool $isVerified = false;

    /**
     * Token d'activation utilisé pour vérifier un compte utilisateur.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $activationToken = null;

    /**
     * Retourne l'identifiant de l'utilisateur.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retourne l'email de l'utilisateur.
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Définit l'email de l'utilisateur.
     */
    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

     /**
     * Retourne l'identifiant unique de l'utilisateur (utilisé par le système de sécurité).
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * Retourne la liste des rôles de l'utilisateur.
     *
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // Garantit que chaque utilisateur a au moins le rôle ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * Définit les rôles de l'utilisateur.
     *
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Retourne le mot de passe haché de l'utilisateur.
     *
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Définit le mot de passe haché de l'utilisateur.
     */
    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Méthode utilisée pour effacer les données sensibles temporaires.
     *
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * Retourne le nom d'utilisateur.
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * Définit le nom d'utilisateur.
     */
    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Retourne l'adresse de livraison.
     */
    public function getDeliveryAddress(): ?string
    {
        return $this->deliveryAddress;
    }

    /**
     * Définit l'adresse de livraison.
     */
    public function setDeliveryAddress(string $deliveryAddress): self
    {
        $this->deliveryAddress = $deliveryAddress;

        return $this;
    }

    /**
     * Vérifie si l'utilisateur a confirmé son compte.
     */
    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    /**
     * Définit si le compte utilisateur est vérifié.
     */
    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * Retourne le Token d'activation du compte.
     */
    public function getActivationToken(): ?string
    {
        return $this->activationToken;
    }

    /**
     * Définit le Token d'activation du compte.
     */
    public function setActivationToken(?string $activationToken): self
    {
        $this->activationToken = $activationToken;

        return $this;
    }
}
