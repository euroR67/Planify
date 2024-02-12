<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updateAt = null;

    #[ORM\OneToMany(targetEntity: Liste::class, mappedBy: 'project', orphanRemoval: true)]
    private Collection $lists;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'members')]
    private Collection $members;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'favoris')]
    private Collection $favorisUsers;

    public function __construct()
    {
        $this->lists = new ArrayCollection();
        $this->members = new ArrayCollection();
        $this->favorisUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdateAt(): ?\DateTimeImmutable
    {
        return $this->updateAt;
    }

    public function setUpdateAt(?\DateTimeImmutable $updateAt): static
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    /**
     * @return Collection<int, Liste>
     */
    public function getLists(): Collection
    {
        return $this->lists;
    }

    public function addList(Liste $list): static
    {
        if (!$this->lists->contains($list)) {
            $this->lists->add($list);
            $list->setProject($this);
        }

        return $this;
    }

    public function removeList(Liste $list): static
    {
        if ($this->lists->removeElement($list)) {
            // set the owning side to null (unless already changed)
            if ($list->getProject() === $this) {
                $list->setProject(null);
            }
        }

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(User $member): static
    {
        if (!$this->members->contains($member)) {
            $this->members->add($member);
            $member->addMember($this);
        }

        return $this;
    }

    public function removeMember(User $member): static
    {
        if ($this->members->removeElement($member)) {
            $member->removeMember($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getFavorisUsers(): Collection
    {
        return $this->favorisUsers;
    }

    public function addFavorisUser(User $favorisUser): static
    {
        if (!$this->favorisUsers->contains($favorisUser)) {
            $this->favorisUsers->add($favorisUser);
            $favorisUser->addFavori($this);
        }

        return $this;
    }

    public function removeFavorisUser(User $favorisUser): static
    {
        if ($this->favorisUsers->removeElement($favorisUser)) {
            $favorisUser->removeFavori($this);
        }

        return $this;
    }
}
