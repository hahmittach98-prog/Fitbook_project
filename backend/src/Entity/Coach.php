<?php

namespace App\Entity;


use App\Repository\CoachRepository;
use Doctrine\DBAL\Types\Types;
use App\Entity\Traits\Timestampable; 
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;


use Vich\UploaderBundle\Mapping\Attribute as Vich;

#[ORM\Table(name: "coachs")]
#[ORM\Entity(repositoryClass: CoachRepository::class)]
#[Vich\Uploadable]
#[ORM\HasLifecycleCallbacks]  

class Coach
{

use Timestampable;   

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Vich\UploadableField(mapping: 'coachs', fileNameProperty: 'imageName', size: 'imageSize')]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;

    #[ORM\Column(nullable: true)]
    private ?int $imageSize = null;

  
    #[ORM\Column(length: 255)]
    private ?string $specialite = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?float $tarifHoraire = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(string $specialite): static
    {
        $this->specialite = $specialite;

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

    public function getTarifHoraire(): ?float
    {
        return $this->tarifHoraire;
    }

    public function setTarifHoraire(?float $tarifHoraire): static
    {
        $this->tarifHoraire = $tarifHoraire;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;

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
