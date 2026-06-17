<?php

namespace App\Entity;

use App\Repository\UserCertificationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: UserCertificationRepository::class)]
class UserCertification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['course:read', 'user:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userCertifications')]
    #[Groups(['course:read', 'user:read'])]
    private ?Certification $certification = null;

    #[ORM\ManyToOne(inversedBy: 'userCertifications')]
    private ?User $usr = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCertification(): ?Certification
    {
        return $this->certification;
    }

    public function setCertification(?Certification $certification): static
    {
        $this->certification = $certification;

        return $this;
    }

    public function getUsr(): ?User
    {
        return $this->usr;
    }

    public function setUsr(?User $usr): static
    {
        $this->usr = $usr;

        return $this;
    }
}
