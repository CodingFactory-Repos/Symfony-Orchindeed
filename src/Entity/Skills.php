<?php

namespace App\Entity;

use App\Repository\SkillsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SkillsRepository::class)]
class Skills
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: users::class, inversedBy: 'skills')]
    private Collection $users;

    #[ORM\ManyToMany(targetEntity: offers::class, inversedBy: 'skills')]
    private Collection $offers;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->offers = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name;
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

    /**
     * @return Collection<int, users>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(users $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
        }

        return $this;
    }

    public function removeUser(users $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }

    /**
     * @return Collection<int, offers>
     */
    public function getOffers(): Collection
    {
        return $this->offers;
    }

    public function addOffer(offers $offer): self
    {
        if (!$this->offers->contains($offer)) {
            $this->offers->add($offer);
        }

        return $this;
    }

    public function removeOffer(offers $offer): self
    {
        $this->offers->removeElement($offer);

        return $this;
    }
}
