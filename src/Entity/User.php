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
use App\Repository\UserRepository;
use App\State\DeletedProcessor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;
use App\State\UserProcessor;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ApiResource(
    normalizationContext: ['groups' => ['user:read']],
    denormalizationContext: ['groups' => ['user:write', 'user-wallet:write']],
    operations: [
        // formats json for avoid the json ld format
        new Get(),
        new GetCollection(security:'is_granted("ROLE_ADMIN")'),
        // new GetCollection(),
        new Post(processor: UserProcessor::class),
        new Put(processor: UserProcessor::class, security:'is_fully_authenticated() && is_granted("ROLE_USER") || is_granted("ROLE_ADMIN") || object == user', denormalizationContext: ['groups'=> 'user:write','user-wallet:write']),
        new Put(processor: DeletedProcessor::class,  name: 'deleted_user', uriTemplate: '/users/{id}/deleted', security:'is_granted("ROLE_ADMIN") or object == user'),
        new Delete(security: 'is_granted("ROLE_ADMIN")')
    ]
)]
#[ApiFilter(BooleanFilter::class, properties: ['deleted'])]
#[UniqueEntity(fields: ['email'], message: 'L\'email est déjà utilisé')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:read', 'voucherUser:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank]
    #[groups(['user:read', 'user:write', 'user-wallet:write'])]
    #[Assert\Email(
        message: 'The email {{ value }} is not a valid email.',
    )]
    private ?string $email = null;

    #[ORM\Column]
    #[groups(['user:read'])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank]
    #[groups(['user:read'])]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[groups(['user:read', 'user:write' , 'user-wallet:write'])]
    private ?string $lname = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[groups(['user:read', 'user:write' , 'user-wallet:write'])]
    private ?string $fname = null;

    #[ORM\Column(nullable: true, length: 100)]
    #[groups(['user:read', 'user:write' , 'user-wallet:write'])]
    private ?string $phone = null;

    #[ORM\Column(nullable: true, type: Types::TEXT)]
    #[groups(['user:read', 'user:write', 'user-wallet:write'])]
    private ?string $adress = null;

    #[groups(['user:read', 'user:write', 'user-wallet:write'])]
    #[ORM\Column(nullable: true, length: 255)]
    private ?string $nickname = null;

    #[groups(['user:read'])]
    #[ORM\Column]
    private ?int $level = null;

    #[groups(['user:read', 'user:write', 'user-wallet:write'])]
    #[ORM\Column]
    private ?int $wallet = null;

    #[ORM\Column]
    #[groups(['user:read','user:write', 'user-wallet:write'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[groups(['user:read','user:write', 'user-wallet:write'])]
    private ?\DateTimeImmutable $updateAt = null;

    #[ORM\Column]
    private ?bool $deleted = null;

    #[ORM\OneToMany(mappedBy: 'userId', targetEntity: UserVoucher::class)]
    private Collection $userVouchers;

    #[ORM\Column]
    #[groups(['user:read','user:write', 'user-wallet:write'])]
    private ?int $trees = null;

    // #[SerializedName('password')]
    #[ORM\Column(nullable: true, length: 255)]
    #[groups(['user:read','user:write', 'user-wallet:write'])]
    #[Assert\Length(
        min: 8,
        minMessage: 'The password need to be at least  {{ limit }} character long.'
    )]
    private ?string $plainPassword = null;

    public function __construct()
    {
        $this->trees = 0;
        $this->level = 0;
        $this->wallet = 0;
        $this->roles = ['ROLE_USER'];
        $this->createdAt = new \DateTimeImmutable();
        $this->updateAt = new \DateTimeImmutable();
        $this->deleted = false;
        $this->userVouchers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
        
    }

    public function getLname(): ?string
    {
        return $this->lname;
    }

    public function setLname(string $lname): self
    {
        $this->lname = $lname;

        return $this;
    }

    public function getFname(): ?string
    {
        return $this->fname;
    }

    public function setFname(string $fname): self
    {
        $this->fname = $fname;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): self
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getWallet(): ?int
    {
        return $this->wallet;
    }

    public function setWallet(int $wallet): self
    {
        $this->wallet = $wallet;

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

    /**
     * @return Collection<int, UserVoucher>
     */
    public function getUserVouchers(): Collection
    {
        return $this->userVouchers;
    }

    public function addUserVoucher(UserVoucher $userVoucher): self
    {
        if (!$this->userVouchers->contains($userVoucher)) {
            $this->userVouchers->add($userVoucher);
            $userVoucher->setUserId($this);
        }

        return $this;
    }

    public function removeUserVoucher(UserVoucher $userVoucher): self
    {
        if ($this->userVouchers->removeElement($userVoucher)) {
            // set the owning side to null (unless already changed)
            if ($userVoucher->getUserId() === $this) {
                $userVoucher->setUserId(null);
            }
        }

        return $this;
    }

    public function getTrees(): ?int
    {
        return $this->trees;
    }

    public function setTrees(int $trees): self
    {
        $this->trees = $trees;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }
}