<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\ApiFilter;
use App\State\DeletedProcessor;
use App\State\UpdatedAtProcessor;

// use ApiPlatform\Doctrine\Orm\Filter\ExistsFilter;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['category:read', 'categoryRubbish:read', 'rubbish:read']],
    denormalizationContext: ['groups' => ['category:write']],
    operations: [
        new Get(),
        new GetCollection(),
        new Post(security: 'is_granted("ROLE_ADMIN")'),
        new Put(processor: DeletedProcessor::class,  name: 'deleted_category', uriTemplate: '/categories/{id}/deleted', security:'is_granted("ROLE_ADMIN")'),
        new Put(processor: UpdatedAtProcessor::class, security: 'is_granted("ROLE_ADMIN")'),
        new Delete(security: 'is_granted("ROLE_ADMIN")')
    ]
)]
#[ApiFilter(BooleanFilter::class, properties: ['deleted'])]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['category:read', 'category:write', 'rubbish:read', 'categoryRubbish:read'])]
    private ?string $name = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updateAt = null;

    #[ORM\Column]
    #[Groups(['category:read', 'category:write'])]
    private ?bool $deleted = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Rubbish::class)]
    #[Groups(['category:read'])]
    private Collection $rubbishList;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->updateAt = new \DateTimeImmutable();
        $this->deleted = false;
        $this->rubbishList = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDeleted(): ?string
    {
        return $this->deleted;
    }

    public function setDeleted(?string $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * @return Collection<int, Rubbish>
     */
    public function getRubbishList(): Collection
    {
        return $this->rubbishList;
    }

    public function addRubbishList(Rubbish $rubbishList): self
    {
        if (!$this->rubbishList->contains($rubbishList)) {
            $this->rubbishList->add($rubbishList);
            $rubbishList->setCategory($this);
        }

        return $this;
    }

    public function removeRubbishList(Rubbish $rubbishList): self
    {
        if ($this->rubbishList->removeElement($rubbishList)) {
            // set the owning side to null (unless already changed)
            if ($rubbishList->getCategory() === $this) {
                $rubbishList->setCategory(null);
            }
        }

        return $this;
    }
}
