<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 */
class User implements UserInterface
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Length(
     *     min=2,
     *     minMessage="Veuillez saisir au moins deux caractéres",
     *     max="30",
     *     maxMessage="Votre pseudo est limité à 30 caractéres"
     *     )
     * @Assert\NotBlank(message="Veuillez entrez un pseudo")
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\Regex(
     *      pattern="/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$/",
     *      message="Utilisez au moins 1 majuscule, 1 minuscule et 1 nombre"
     *      )
     * @Assert\NotNull(message="Veuillez saisir un mot de passe")
     * @Assert\NotBlank(message="Veuillez saisir un mot de passe")
     * @Assert\Length(
     *     max=30,
     *     maxMessage="Votre mot de passe est limité à 30 caractéres"
     * )
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Length(
     *     min=2,
     *     minMessage="Veuillez saisir au moins deux caractéres",
     *     max="30",
     *     maxMessage="Votre nom est limité à 30 caractéres"
     *     )
     * @Assert\NotBlank(message="Veuillez entrez un nom")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\Length(
     *     min=2,
     *     minMessage="Veuillez saisir au moins deux caractéres",
     *     max="30",
     *     maxMessage="Votre prénom est limité à 30 caractéres"
     *     )
     * @Assert\NotBlank(message="Veuillez entrez un prénomm")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true
     *     )
     * @Assert\NotBlank(message="Veuillez saisir un mot de passe")
     */
    private $mail;

    /**
     * @ORM\Column(type="boolean")
     */
    private $administrator;

    /**
     * @ORM\Column(type="boolean")
     */
    private $actif;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Excursion", mappedBy="RegisterExcursion")
     */
    private $excursions;

    /**
     * @ORM\Column(type="string", length=10000, nullable=true)
     * @Assert\File(mimeTypes={ "image/jpg", "image/jpeg", "image/png"})
     */
    public $photo_file;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Site", inversedBy="user")
     */
    private $site;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    private $resetPassword;


    public function __construct()
    {
        $this->excursions = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
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
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getAdministrator(): ?bool
    {
        return $this->administrator;
    }

    public function setAdministrator(bool $administrator): self
    {
        $this->administrator = $administrator;

        return $this;
    }

    public function getActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    /**
     * @return Collection|Excursion[]
     */
    public function getExcursions(): Collection
    {
        return $this->excursions;
    }

    public function addExcursion(Excursion $excursion): self
    {
        if (!$this->excursions->contains($excursion)) {
            $this->excursions[] = $excursion;
            $excursion->addRegisterExcursion($this);
        }

        return $this;
    }

    public function removeExcursion(Excursion $excursion): self
    {
        if ($this->excursions->contains($excursion)) {
            $this->excursions->removeElement($excursion);
            $excursion->removeRegisterExcursion($this);
        }

        return $this;
    }

    public function getPhotoFile(): ?string
    {
        return $this->photo_file;
    }

    public function setPhotoFile(?string $photo_file): self
    {
        $this->photo_file = $photo_file;

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

    public function getResetPassword(): ?string
    {
        return $this->resetPassword;
    }

    public function setResetPassword(?string $resetPassword): self
    {
        $this->resetPassword = $resetPassword;

        return $this;
    }
}
