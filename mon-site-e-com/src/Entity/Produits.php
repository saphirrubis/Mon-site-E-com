<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\ProduitsRepository;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\HttpFoundation\File\File;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ProduitsRepository::class)]
#[Vich\Uploadable]
#[ApiResource(
    operations: [
               new Get(normalizationContext: ['groups' => 'read:prod','recherche:produit']),
               new GetCollection(normalizationContext: ['groups' => 'read:detail'])
    ],
    order: ['year' => 'DESC', 'city' => 'ASC'],
    paginationEnabled: false,
)]
#[ApiFilter(SearchFilter::class, properties: ['recherche' => 'exact'])]
class Produits
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['read:prod', 'read:detail' , 'recherche:produit'])]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read:detail','recherche:produit'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['read:prod', 'read:detail'])]
    private ?float $prix = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['read:detail'])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\Column(length: 255)]
    #[Groups(['read:prod', 'read:detail'])]
    private ?string $slug = null;

    #[ORM\Column]
    private bool $online = false;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['read:prod', 'read:detail'])]
    private ?string $attachement = null;

    #[Vich\UploadableField( mapping:"product_attachements", fileNameProperty: "attachement")]
    private ?File $attachementFile = null;


    #[ORM\ManyToOne(inversedBy: 'produits')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read:prod', 'read:detail'])]
    private ?CategoryShop $category = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function isOnline(): bool
    {
        return $this->online;
    }

    public function setOnline(bool $online): self
    {
        $this->online = $online;

        return $this;
    }

    public function getAttachement(): ?string
    {
        return $this->attachement;
    }

    public function setAttachement(string $attachement): self
    {
        $this->attachement = $attachement;

        return $this;
    }
    public function getAttachementFile()
    {
        return $this->attachementFile;
    }
    public function setAttachementFile(File $attachementFile = null)
    {
        $this->attachementFile = $attachementFile;
        if (null !== $attachementFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getCategory(): ?CategoryShop
    {
        return $this->category;
    }

    public function setCategory(?CategoryShop $category): self
    {
        $this->category = $category;

        return $this;
    }
}
