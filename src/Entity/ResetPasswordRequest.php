<?php

namespace App\Entity;

use App\Repository\ResetPasswordRequestRepository;
use Doctrine\ORM\Mapping as ORM;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestInterface;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestTrait;

#[ORM\Entity(repositoryClass: ResetPasswordRequestRepository::class)]
/**
 * Entité représentant une demande de réinitialisation de mot de passe.
 * 
 * Cette entité est utilisée par le bundle SymfonyCasts ResetPassword pour gérer la logique
 * de création, validation et expiration des Tokens de réinitialisation.
 */
class ResetPasswordRequest implements ResetPasswordRequestInterface
{
    use ResetPasswordRequestTrait;

    /**
     * Identifiant unique de la demande de réinitialisation.
     *
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Utilisateur associé à la demande de réinitialisation.
     *
     * @var User|null
     */
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * Constructeur de la demande de réinitialisation de mot de passe.
     *
     * @param User $user L'utilisateur qui a demandé la réinitialisation
     * @param \DateTimeInterface $expiresAt La date d'expiration du jeton
     * @param string $selector Le sélecteur utilisé pour retrouver la demande
     * @param string $hashedToken Le Token de réinitialisation hashé
     */
    public function __construct(User $user, \DateTimeInterface $expiresAt, string $selector, string $hashedToken)
    {
        $this->user = $user;
        $this->initialize($expiresAt, $selector, $hashedToken);
    }

    /**
     * Retourne l'identifiant de la demande.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retourne l'utilisateur associé à la demande.
     *
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
}
