<?php

namespace App\Entity;

use App\Repository\UserModuleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserModuleRepository::class)]
class UserModule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userModules')]
    private ?Module $module = null;

    #[ORM\ManyToOne(inversedBy: 'userModules')]
    private ?User $usr = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $submittedFlag = null;

    #[ORM\Column]
    private ?bool $solved = false;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUsr(): ?User
    {
        return $this->usr;
    }

    public function setUsr(?User $usr): static
    {
        $this->usr = $usr;

        return $this;
    }

    public function getSubmittedFlag(): ?string
    {
        return $this->submittedFlag;
    }

    public function setSubmittedFlag(string $submittedFlag): static
    {
        $this->submittedFlag = $submittedFlag;

        return $this;
    }

    public function isSolved(): ?bool
    {
        return $this->solved;
    }

    public function setSolved(bool $solved): static
    {
        $this->solved = $solved;

        return $this;
    }
}
