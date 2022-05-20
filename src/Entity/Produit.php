<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[NotBlank (message: "Merci de renseigner un nom de libellé valide (entre 3 et 50 caractères).")]
    #[Length (min: 3, max: 50)]
    #[ORM\Column(type: 'string', length: 50)]
    private $libelle;

    #[NotBlank (message: "Merci de renseigner une description valide (10 caractères uniquement).")]
    #[Length (min: 10, max: 10)]
    #[ORM\Column(type: 'string', length: 10)]
    private $reference;

    #[NotBlank (message: "Merci de renseigner un prix valide.")]
    #[Positive]
    #[ORM\Column(type: 'decimal', scale: 2)]
    private $prix;

    #[NotBlank (message: "Merci de renseigner un stock valide.")]
    #[Positive]
    #[ORM\Column(type: 'integer')]
    private $stock;

    #[NotBlank (message: "Merci de renseigner une date.")]
    #[ORM\Column(type: 'datetime')]
    private $date_ajout;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getDateAjout(): ?\DateTimeInterface
    {
        return $this->date_ajout;
    }

    public function setDateAjout(\DateTimeInterface $date_ajout): self
    {
        $this->date_ajout = $date_ajout;

        return $this;
    }
}
