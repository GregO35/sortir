<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExcursionRepository")
 */
class Excursion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startDate;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $duration;

    /**
     * @ORM\Column(type="datetime")
     */
    private $endDate;

    /**
     * @ORM\Column(type="integer")
     */
    private $registrationNumberMax;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $excursionState;

    /**
     * @ORM\Column(type="string", length=250, nullable=true)
     */
    private $urlPicture;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="excursions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $organizer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\State", inversedBy="excursions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $State;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="excursions")
     */
    private $RegisterExcursion;

    public function __construct()
    {
        $this->RegisterExcursion = new ArrayCollection();
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

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getRegistrationNumberMax(): ?int
    {
        return $this->registrationNumberMax;
    }

    public function setRegistrationNumberMax(int $registrationNumberMax): self
    {
        $this->registrationNumberMax = $registrationNumberMax;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getExcursionState(): ?int
    {
        return $this->excursionState;
    }

    public function setExcursionState(?int $excursionState): self
    {
        $this->excursionState = $excursionState;

        return $this;
    }

    public function getUrlPicture(): ?string
    {
        return $this->urlPicture;
    }

    public function setUrlPicture(?string $urlPicture): self
    {
        $this->urlPicture = $urlPicture;

        return $this;
    }

    public function getOrganizer(): ?User
    {
        return $this->organizer;
    }

    public function setOrganizer(?User $organizer): self
    {
        $this->organizer = $organizer;

        return $this;
    }

    public function getState(): ?State
    {
        return $this->State;
    }

    public function setState(?State $State): self
    {
        $this->State = $State;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getRegisterExcursion(): Collection
    {
        return $this->RegisterExcursion;
    }

    public function addRegisterExcursion(User $registerExcursion): self
    {
        if (!$this->RegisterExcursion->contains($registerExcursion)) {
            $this->RegisterExcursion[] = $registerExcursion;
        }

        return $this;
    }

    public function removeRegisterExcursion(User $registerExcursion): self
    {
        if ($this->RegisterExcursion->contains($registerExcursion)) {
            $this->RegisterExcursion->removeElement($registerExcursion);
        }

        return $this;
    }



}
