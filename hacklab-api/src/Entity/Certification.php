<?php

namespace App\Entity;

use App\Repository\CertificationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CertificationRepository::class)]
class Certification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20, unique: true)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    /**
     * @var Collection<int, UserCertification>
     */
    #[ORM\OneToMany(targetEntity: UserCertification::class, mappedBy: 'certification')]
    private Collection $userCertifications;

    #[ORM\OneToOne]
    private ?Module $module = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    public function __construct()
    {
        $this->userCertifications = new ArrayCollection();
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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, UserCertification>
     */
    public function getUserCertifications(): Collection
    {
        return $this->userCertifications;
    }

    public function addUserCertification(UserCertification $userCertification): static
    {
        if (!$this->userCertifications->contains($userCertification)) {
            $this->userCertifications->add($userCertification);
            $userCertification->setCertification($this);
        }

        return $this;
    }

    public function removeUserCertification(UserCertification $userCertification): static
    {
        if ($this->userCertifications->removeElement($userCertification)) {
            // set the owning side to null (unless already changed)
            if ($userCertification->getCertification() === $this) {
                $userCertification->setCertification(null);
            }
        }

        return $this;
    }

    public function getModule(): ?Module
    {
        return $this->module;
    }

    public function setModule(?Module $module): static
    {
        $this->module = $module;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
