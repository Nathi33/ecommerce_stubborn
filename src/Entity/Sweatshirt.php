<?php

namespace App\Entity;

use App\Repository\SweatshirtRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SweatshirtRepository::class)]
/**
 * Représente un sweat-shirt disponible dans la boutique.
 */
class Sweatshirt
{
    /**
     * Identifiant unique du sweat-shirt.
     *
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * Nom du sweat-shirt.
     *
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * Prix du sweat-shirt.
     *
     * @var float|null
     */
    #[ORM\Column]
    private ?float $price = null;

    /**
     * Stock disponible pour la taille XS.
     *
     * @var int|null
     */
    #[ORM\Column]
    private ?int $stockXS = null;

    /**
     * Stock disponible pour la taille S.
     *
     * @var int|null
     */
    #[ORM\Column]
    private ?int $stockS = null;

    /**
     * Stock disponible pour la taille M.
     *
     * @var int|null
     */
    #[ORM\Column]
    private ?int $stockM = null;

    /**
     * Stock disponible pour la taille L.
     *
     * @var int|null
     */
    #[ORM\Column]
    private ?int $stockL = null;

    /**
     * Stock disponible pour la taille XL.
     *
     * @var int|null
     */
    #[ORM\Column]
    private ?int $stockXL = null;

    /**
     * URL ou nom de fichier de l'image du sweat-shirt.
     *
     * @var string|null
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    /**
     * Indique si le sweat-shirt est mis en avant.
     *
     * @var bool|null
     */
    #[ORM\Column(type: 'boolean')]
    private ?bool $featured = false;

    /**
     * Retourne l'identifiant du sweat-shirt.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Retourne le nom du sweat-shirt.
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Définit le nom du sweat-shirt.
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Retourne le prix du sweat-shirt.
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * Définit le prix du sweat-shirt.
     */
    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Retourne le stock XS.
     */
    public function getStockXS(): ?int
    {
        return $this->stockXS;
    }

    /**
     * Définit le stock XS.
     */
    public function setStockXS(int $stockXS): static
    {
        $this->stockXS = $stockXS;

        return $this;
    }

     /**
     * Retourne le stock S.
     */
    public function getStockS(): ?int
    {
        return $this->stockS;
    }

    /**
     * Définit le stock S.
     */
    public function setStockS(int $stockS): static
    {
        $this->stockS = $stockS;

        return $this;
    }

     /**
     * Retourne le stock M.
     */
    public function getStockM(): ?int
    {
        return $this->stockM;
    }

    /**
     * Définit le stock M.
     */
    public function setStockM(int $stockM): static
    {
        $this->stockM = $stockM;

        return $this;
    }

     /**
     * Retourne le stock L.
     */
    public function getStockL(): ?int
    {
        return $this->stockL;
    }

    /**
     * Définit le stock L.
     */
    public function setStockL(int $stockL): static
    {
        $this->stockL = $stockL;

        return $this;
    }

     /**
     * Retourne le stock XL.
     */
    public function getStockXL(): ?int
    {
        return $this->stockXL;
    }

    /**
     * Définit le stock XL.
     */
    public function setStockXL(int $stockXL): static
    {
        $this->stockXL = $stockXL;

        return $this;
    }

    /**
     * Retourne le nom ou l'URL de l'image.
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * Définit le nom ou l'URL de l'image.
     */
    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Indique si le produit est mis en avant.
     */
    public function isFeatured(): ?bool
    {
        return $this->featured;
    }
    
    /**
     * Définit si le produit doit être mis en avant.
     */
    public function setFeatured(bool $featured): static
    {
        $this->featured = $featured;

        return $this;
    }
}
