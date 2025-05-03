<?php

namespace App\Repository;

use App\Entity\ResetPasswordRequest;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestInterface;
use SymfonyCasts\Bundle\ResetPassword\Persistence\Repository\ResetPasswordRequestRepositoryTrait;
use SymfonyCasts\Bundle\ResetPassword\Persistence\ResetPasswordRequestRepositoryInterface;

/**
 * Repository pour l'entité ResetPasswordRequest.
 *
 * Ce repository gère les demandes de réinitialisation de mot de passe en implémentant
 * les fonctionnalités nécessaires via l'interface ResetPasswordRequestRepositoryInterface
 * et le trait ResetPasswordRequestRepositoryTrait.
 *
 * @extends ServiceEntityRepository<ResetPasswordRequest>
 */
class ResetPasswordRequestRepository extends ServiceEntityRepository implements ResetPasswordRequestRepositoryInterface
{
    use ResetPasswordRequestRepositoryTrait;

    /**
     * Constructeur du repository.
     *
     * @param ManagerRegistry $registry Le registre de gestionnaire d'entités Doctrine
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResetPasswordRequest::class);
    }

    /**
     * Crée une instance de demande de réinitialisation de mot de passe.
     *
     * @param User              $user        L'utilisateur pour lequel la demande est créée
     * @param \DateTimeInterface $expiresAt   Date d'expiration de la demande
     * @param string            $selector    Sélecteur unique de la demande
     * @param string            $hashedToken Jeton sécurisé, déjà haché
     *
     * @return ResetPasswordRequestInterface L'objet ResetPasswordRequest prêt à être persisté
     */
    public function createResetPasswordRequest(object $user, \DateTimeInterface $expiresAt, string $selector, string $hashedToken): ResetPasswordRequestInterface
    {
        return new ResetPasswordRequest($user, $expiresAt, $selector, $hashedToken);
    }
}
