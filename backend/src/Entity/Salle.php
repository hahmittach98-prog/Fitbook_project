<?php

namespace App\Entity;


use App\Repository\SalleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Traits\Timestampable; 
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Attribute as Vich;


#[ORM\Table(name: "salles")]
#[ORM\Entity(repositoryClass: SalleRepository::class)]
#[Vich\Uploadable]
#[ORM\HasLifecycleCallbacks]  

class Salle
{

use Timestampable;   

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

     #[Vich\UploadableField(mapping: 'salles', fileNameProperty: 'imageName', size: 'imageSize')]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;

    #[ORM\Column(nullable: true)]
    private ?int $imageSize = null;

    
    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $ville = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column]
    private ?int $capacite = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $gestionnaire = null;

    /**
     * @var Collection<int, Cours>
     */
    #[ORM\ManyToMany(targetEntity: Cours::class)]
    private Collection $cours;

    /**
     * @var Collection<int, Coach>
     */
    #[ORM\ManyToMany(targetEntity: Coach::class)]
    private Collection $coachs;

    public function __construct()
    {
        $this->cours = new ArrayCollection();
        $this->coachs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCapacite(): ?int
    {
        return $this->capacite;
    }

    public function setCapacite(int $capacite): static
    {
        $this->capacite = $capacite;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    public function getGestionnaire(): ?User
    {
        return $this->gestionnaire;
    }

    public function setGestionnaire(?User $gestionnaire): static
    {
        $this->gestionnaire = $gestionnaire;

        return $this;
    }

    /**
     * @return Collection<int, Cours>
     */
    public function getCours(): Collection
    {
        return $this->cours;
    }

    public function addCours(Cours $cours): static
    {
        if (!$this->cours->contains($cours)) {
            $this->cours->add($cours);
        }

        return $this;
    }

    public function removeCours(Cours $cours): static
    {
        $this->cours->removeElement($cours);

        return $this;
    }

    /**
     * @return Collection<int, Coach>
     */
    public function getCoachs(): Collection
    {
        return $this->coachs;
    }

    public function addCoach(Coach $coach): static
    {
        if (!$this->coachs->contains($coach)) {
            $this->coachs->add($coach);
        }

        return $this;
    }

    public function removeCoach(Coach $coach): static
    {
        $this->coachs->removeElement($coach);

        return $this;
    }
     public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;
        if (null !== $imageFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }
    public function getImageFile(): ?File {

         return $this->imageFile; 
         }

    public function setImageName(?string $imageName): void { 
        $this->imageName = $imageName;
         }

    public function getImageName(): ?string { 
        
    return $this->imageName; 
    
    }

    public function setImageSize(?int $imageSize): void { 
        
    $this->imageSize = $imageSize; 
    
    }

    public function getImageSize(): ?int {
        
    return $this->imageSize;
    
    }
}
