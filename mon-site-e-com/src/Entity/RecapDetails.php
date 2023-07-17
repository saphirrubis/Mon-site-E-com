<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\RecapDetailsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecapDetailsRepository::class)]
#[ApiResource()]
class RecapDetails
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'recapDetails')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $orderProduit = null;

    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column(length: 255)]
    private ?string $produit = null;

    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\Column]
    private ?float $totalRecap = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderProduit(): ?Order
    {
        return $this->orderProduit;
    }

    public function setOrderProduit(?Order $orderProduit): self
    {
        $this->orderProduit = $orderProduit;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getProduit(): ?string
    {
        return $this->produit;
    }

    public function setProduit(string $produit): self
    {
        $this->produit = $produit;

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

    public function getTotalRecap(): ?float
    {
        return $this->totalRecap;
    }

    public function setTotalRecap(float $totalRecap): self
    {
        $this->totalRecap = $totalRecap;

        return $this;
    }
}
