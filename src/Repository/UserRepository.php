<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * Repository pour l'entité User.
 *
 * Fournit des méthodes d'accès aux utilisateurs enregistrés en base de données.
 * Implémente PasswordUpgraderInterface pour permettre la mise à jour automatique des mots de passe hashés.
 *
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    /**
     * Constructeur du repository User.
     *
     * @param ManagerRegistry $registry Le registre des gestionnaires Doctrine.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

     /**
     * Met à jour automatiquement (rehash) le mot de passe de l'utilisateur.
     *
     * Cette méthode est appelée automatiquement par Symfony si la stratégie de mise à jour
     * de mot de passe est activée. Elle sert à rehasher les mots de passe selon les nouvelles
     * règles de sécurité définies.
     *
     * @param PasswordAuthenticatedUserInterface $user L'utilisateur dont le mot de passe doit être mis à jour.
     * @param string $newHashedPassword Le nouveau mot de passe hashé.
     *
     * @throws UnsupportedUserException Si l'utilisateur passé en paramètre n'est pas une instance de User.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }
}
