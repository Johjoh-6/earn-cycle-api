<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use App\Controller\VoucherUserController;
use App\Repository\UserVoucherRepository;
use App\State\DeletedProcessor;
use App\State\UpdatedAtProcessor;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserVoucherRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['userVoucher:read']],
    denormalizationContext: ['groups' => ['userVoucher:write']],
    operations: [
        new Get(security: 'is_granted("ROLE_USER") && object.getUserId() == user'),
        new GetCollection(security: 'is_granted("ROLE_ADMIN") || is_granted("ROLE_USER") && object.getUserId() == user'),
        new GetCollection(controller: VoucherUserController::class,  name: 'voucher_by_user', uriTemplate: '/user_vouchers_list', security: 'is_granted("ROLE_USER") && object.getUserId() == user', normalizationContext: ['groups' => ['userVoucher:read']]),
        new Post(security: 'is_granted("ROLE_USER")'),
        new Put(processor: UpdatedAtProcessor::class, security: 'is_granted("ROLE_USER") && object.getUserId() == user', denormalizationContext: ['groups' => ['userVoucherAdmin:write']]),
        new Put(processor: DeletedProcessor::class,  name: 'deleted_user_voucher', uriTemplate: '/user_vouchers/{id}/deleted',  security: 'is_granted("ROLE_USER") && object.getUserId() == user',  denormalizationContext: ['groups' => ['userVoucherAdmin:write']]),
        new Delete(security: 'is_granted("ROLE_ADMIN")',  denormalizationContext: ['groups' => ['userVoucherAdmin:write']])
    ]
)]
#[ApiFilter(BooleanFilter::class, properties: ['deleted'])]
#[ApiFilter(SearchFilter::class, properties: ['voucherId.id' => 'exact', 'userId' => 'exact'])]
class UserVoucher
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['userVoucher:read', 'userVoucherAdmin:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['userVoucher:read', 'userVoucher:write','userVoucherAdmin:read', 'userVoucherAdmin:write'])]
    private ?Voucher $voucherId = null;

    #[ORM\ManyToOne(inversedBy: 'userVouchers')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['userVoucher:read', 'userVoucher:write', 'userVoucherAdmin:read', 'userVoucherAdmin:write'])]
    private ?User $userId = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Groups(['userVoucher:read', 'userVoucher:write', 'userVoucherAdmin:read', 'userVoucherAdmin:write'])]
    private ?int $claim = null;

    #[ORM\Column]
    #[Groups(['userVoucherAdmin:read', 'userVoucherAdmin:write'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Groups(['userVoucherAdmin:read', 'userVoucherAdmin:write'])]
    private ?\DateTimeImmutable $updateAt = null;

    #[ORM\Column]
    #[Groups(['userVoucherAdmin:read', 'userVoucherAdmin:write'])]
    private ?bool $deleted = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updateAt = new \DateTimeImmutable();
        $this->deleted = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVoucherId(): ?Voucher
    {
        return $this->voucherId;
    }

    public function setVoucherId(?Voucher $voucherId): self
    {
        $this->voucherId = $voucherId;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getClaim(): ?int
    {
        return $this->claim;
    }

    public function setClaim(int $claim): self
    {
        $this->claim = $claim;

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
