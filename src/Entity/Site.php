<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SiteRepository")
 */
class Site
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="site")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Excursion", mappedBy="site")
     */
    private $excursion;

    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->excursion = new ArrayCollection();
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
     * @return Collection|User[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
            $user->setSite($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->user->contains($user)) {
            $this->user->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getSite() === $this) {
                $user->setSite(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Excursion[]
     */
    public function getExcursion(): Collection
    {
        return $this->excursion;
    }

    public function addExcursion(Excursion $excursion): self
    {
        if (!$this->excursion->contains($excursion)) {
            $this->excursion[] = $excursion;
            $excursion->setSite($this);
        }

        return $this;
    }

    public function removeExcursion(Excursion $excursion): self
    {
        if ($this->excursion->contains($excursion)) {
            $this->excursion->removeElement($excursion);
            // set the owning side to null (unless already changed)
            if ($excursion->getSite() === $this) {
                $excursion->setSite(null);
            }
        }

        return $this;
    }
}
