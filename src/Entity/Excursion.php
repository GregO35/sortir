<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
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
     * @Assert\Length(
     *     min=5,
     *     max=30,
     *     minMessage="Le nom de votre sortie doit avoir au moins 5 caractéres",
     *     maxMessage="Le nom de votre sortie doit avoir au plus 30 caractéres"
     * )
     * @Assert\NotBlank(message="Merci de renseigner un nom pour votre sortie")
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
     * @Assert\LessThanOrEqual(12,message="Il ne peut y avoir que 12 participants à une sortie maximum!")
     * @Assert\GreaterThanOrEqual(2,message="Il doit y avoir au moins 2 participants à une sortie!")
     * @Assert\NotBlank(message="Merci de renseigner un nombre de participants")
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

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $cancelMessage;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Site", inversedBy="excursion")
     */
    private $site;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Place", inversedBy="excursion")
     */
    private $place;

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

    public function getCancelMessage(): ?string
    {
        return $this->cancelMessage;
    }

    public function setCancelMessage(?string $cancelMessage): self
    {
        $this->cancelMessage = $cancelMessage;

        return $this;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): self
    {
        $this->site = $site;

        return $this;
    }

    public function getPlace(): ?Place
    {
        return $this->place;
    }

    public function setPlace(?Place $place): self
    {
        $this->place = $place;

        return $this;
    }



}
