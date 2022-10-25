<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use App\Controller\RefreshDbController;
use App\Repository\RubbishRepository;
use App\Repository\UserRepository;
use App\State\UpdatedAtProcessor;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RubbishRepository::class)]
#[ApiResource(paginationItemsPerPage: 100)]
#[ApiResource(
    normalizationContext: ['groups' => ['rubbish:read']],
    denormalizationContext: ['groups' => ['rubbish:write']],
    operations: [
        new Get(),
        // fetch Api
        new Get(security: 'is_granted("ROLE_ADMIN")', name: 'refresh-db', uriTemplate: '/api/rubbish/refresh-db/', controller: RefreshDbController::class),
        new GetCollection(),
        new Post(security: 'is_fully_authenticated()'),
        new Put(processor: UpdatedAtProcessor::class, security:'is_granted("ROLE_ADMIN") or object.createdBy == user'),
        new Delete(security: 'is_granted("ROLE_ADMIN")')
    ]
)]
#[ApiFilter(BooleanFilter::class, properties: ['deleted'])]
class Rubbish
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'rubbishList')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['rubbish:read', 'rubbish:write'])]
    private ?Category $category = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    #[Groups(['rubbish:read', 'rubbish:write'])]
    private ?string $longitude = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    #[Groups(['rubbish:read', 'rubbish:write'])]
    private ?string $latitude = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['rubbish:read', 'rubbish:write'])]
    private ?string $nbStreet = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['rubbish:read', 'rubbish:write'])]
    private ?string $streetName = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['rubbish:read', 'rubbish:write'])]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['rubbish:read', 'rubbish:write'])]
    private ?string $country = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank]
    #[Groups(['rubbish:read', 'rubbish:write'])]
    private ?string $postalCode = null;

    #[ORM\Column]
    private ?bool $certified = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $createdBy = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updateAt = null;

    #[ORM\Column]
    private ?bool $deleted = null;

    public function __construct()
    {
        $this->certified = false;
        $this->createdAt = new \DateTimeImmutable();
        $this->updateAt = new \DateTimeImmutable();
        $this->deleted = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getNbStreet(): ?string
    {
        return $this->nbStreet;
    }

    public function setNbStreet(string $nbStreet): self
    {
        $this->nbStreet = $nbStreet;

        return $this;
    }

    public function getStreetName(): ?string
    {
        return $this->streetName;
    }

    public function setStreetName(string $streetName): self
    {
        $this->streetName = $streetName;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function isCertified(): ?bool
    {
        return $this->certified;
    }

    public function setCertified(bool $certified): self
    {
        $this->certified = $certified;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeImmutable
    {
        return $this->updateAt;
    }

    public function setUpdateAt(\DateTimeImmutable $updateAt): self
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    public function isDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }
}
