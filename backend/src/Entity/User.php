<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource]
#[ORM\Table(name: "users")]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'Un compte existe déjà avec cet email')]

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Assert\NotBlank()]
    #[Assert\Email()]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 50)]
    private ?string $nom = null;

    #[ORM\Column(length: 50)]
     #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 50)]
    private ?string $prenom = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column]
    private ?bool $isVerified = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateInscription = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $tokenConfirmation = null;

    /**
     * @var Collection<int, Coach>
     */
    #[ORM\ManyToMany(targetEntity: Coach::class)]
    private Collection $favorisCoach;

    /**
     * @var Collection<int, Cours>
     */
    #[ORM\ManyToMany(targetEntity: Cours::class)]
    private Collection $favorisCours;

    /**
     * @var Collection<int, Salle>
     */
    #[ORM\ManyToMany(targetEntity: Salle::class)]
    private Collection $favorisSalles;

    public function __construct()
    {
        $this->favorisCoach = new ArrayCollection();
        $this->favorisCours = new ArrayCollection();
        $this->favorisSalles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
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

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0" . self::class . "\0password"] = hash('crc32c', $this->password);
        
        return $data;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // @deprecated, to be removed when upgrading to Symfony 8
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function isVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getDateInscription(): ?\DateTimeImmutable
    {
        return $this->dateInscription;
    }

    public function setDateInscription(\DateTimeImmutable $dateInscription): static
    {
        $this->dateInscription = $dateInscription;

        return $this;
    }

    public function getTokenConfirmation(): ?string
    {
        return $this->tokenConfirmation;
    }

    public function setTokenConfirmation(?string $tokenConfirmation): static
    {
        $this->tokenConfirmation = $tokenConfirmation;

        return $this;
    }

    /**
     * @return Collection<int, Coach>
     */
    public function getFavorisCoachs(): Collection
    {
        return $this->favorisCoach;
    }

    public function addFavorisCoach(Coach $favorisCoach): static
    {
        if (!$this->favorisCoach->contains($favorisCoach)) {
            $this->favorisCoach->add($favorisCoach);
        }

        return $this;
    }

    public function removeFavorisCoach(Coach $favorisCoach): static
    {
        $this->favorisCoach->removeElement($favorisCoach);

        return $this;
    }

    /**
     * @return Collection<int, Cours>
     */
    public function getFavorisCours(): Collection
    {
        return $this->favorisCours;
    }

    public function addFavorisCour(Cours $favorisCour): static
    {
        if (!$this->favorisCours->contains($favorisCour)) {
            $this->favorisCours->add($favorisCour);
        }

        return $this;
    }

    public function removeFavorisCour(Cours $favorisCour): static
    {
        $this->favorisCours->removeElement($favorisCour);

        return $this;
    }

    /**
     * @return Collection<int, Salle>
     */
    public function getFavorisSalles(): Collection
    {
        return $this->favorisSalles;
    }

    public function addFavorisSalle(Salle $favorisSalle): static
    {
        if (!$this->favorisSalles->contains($favorisSalle)) {
            $this->favorisSalles->add($favorisSalle);
        }

        return $this;
    }

    public function removeFavorisSalle(Salle $favorisSalle): static
    {
        $this->favorisSalles->removeElement($favorisSalle);

        return $this;
    }
}
