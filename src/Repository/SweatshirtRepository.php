<?php

namespace App\Repository;

use App\Entity\Sweatshirt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository pour l'entité Sweatshirt.
 *
 * Ce repository fournit des méthodes d'accès à la base de données pour les objets de type Sweatshirt.
 * Il hérite des méthodes standard de Doctrine telles que find(), findAll(), findBy(), findOneBy(), etc.
 *
 * @extends ServiceEntityRepository<Sweatshirt>
 */
class SweatshirtRepository extends ServiceEntityRepository
{
    /**
     * Constructeur du repository Sweatshirt.
     *
     * @param ManagerRegistry $registry Le registre du gestionnaire Doctrine
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sweatshirt::class);
    }
}
