<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use App\Repository\VoucherRepository;
use App\State\DeletedProcessor;
use App\State\UpdatedAtProcessor;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VoucherRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['voucher:read']],
    denormalizationContext: ['groups' => ['voucher:write']],
    operations: [
        new Get(normalizationContext: ['groups' => ['voucherUser:read']]),
        new GetCollection(normalizationContext: ['groups' => ['voucherUser:read']]),
        new Post(security: 'is_granted("ROLE_ADMIN")'),
        new Put(processor: UpdatedAtProcessor::class, security: 'is_granted("ROLE_ADMIN")'),
        new Put(processor: DeletedProcessor::class,  name: 'deleted_voucher', uriTemplate: '/vouchers/{id}/deleted', security: 'is_granted("ROLE_ADMIN")'),
        new Delete(security: 'is_granted("ROLE_ADMIN")')
    ]
)]
#[ApiFilter(BooleanFilter::class, properties: ['deleted'])]
#[ApiFilter(DateFilter::class, properties:['endDate'])]
class Voucher
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['voucher:read', 'voucherUser:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 10,
        minMessage: 'The description need to be at least  {{ limit }} character long.',
        max: 255,
        maxMessage: 'The description cannot be longer than {{ limit }} characters.'
    )]
    #[Groups(['voucher:read', 'voucher:write', 'voucherUser:read', 'partner:read'])]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'vouchers')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['voucher:read', 'voucher:write', 'voucherUser:read'])]
    #[ApiFilter(SearchFilter::class, strategy: 'ipartial', properties: ['partner.name'])]
    private ?Partner $partnerId = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Groups(['voucher:read', 'voucher:write', 'voucherUser:read', 'userVoucher:read', 'partner:read'])]
    private ?int $limitUse = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Groups(['voucher:read', 'voucher:write', 'voucherUser:read', 'userVoucher:read', 'partner:read'])]
    private ?\DateTimeImmutable $startDate = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    #[Groups(['voucher:read', 'voucher:write', 'voucherUser:read', 'userVoucher:read', 'partner:read'])]
    private ?\DateTimeImmutable $endDate = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updateAt = null;

    #[ORM\Column]
    #[Groups(['voucher:read'])]
    private ?bool $deleted = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 8,
        minMessage: 'The title need to be at least  {{ limit }} character long.',
        max: 255,
        maxMessage: 'The title cannot be longer than {{ limit }} characters.'
    )]
    #[Groups(['voucher:read', 'voucher:write', 'voucherUser:read', 'partner:read'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['voucher:read', 'voucher:write', 'voucherUser:read', 'userVoucher:read', 'partner:read'])]
    #[Assert\NotBlank]
    private ?int $price = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['voucher:read', 'voucher:write', 'voucherUser:read', 'userVoucher:read', 'partner:read'])]
    private ?string $image = null;

    public function __construct()
    {
        $this->price = 0;
        $this->createdAt = new \DateTimeImmutable();
        $this->updateAt = new \DateTimeImmutable();
        $this->deleted = false;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPartnerId(): ?Partner
    {
        return $this->partnerId;
    }

    public function setPartnerId(?Partner $partnerId): self
    {
        $this->partnerId = $partnerId;

        return $this;
    }

    public function getLimitUse(): ?int
    {
        return $this->limitUse;
    }

    public function setLimitUse(int $limitUse): self
    {
        $this->limitUse = $limitUse;

        return $this;
    }

    public function getStartDate(): ?\DateTimeImmutable
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeImmutable $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeImmutable
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeImmutable $endDate): self
    {
        $this->endDate = $endDate;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }
}
