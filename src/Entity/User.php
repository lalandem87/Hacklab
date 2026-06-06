<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $gamertag = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column]
    private ?int $pointEarn = null;

    /**
     * @var Collection<int, UserModule>
     */
    #[ORM\OneToMany(targetEntity: UserModule::class, mappedBy: 'usr')]
    private Collection $userModules;

    /**
     * @var Collection<int, UserCertification>
     */
    #[ORM\OneToMany(targetEntity: UserCertification::class, mappedBy: 'usr')]
    private Collection $userCertifications;

    public function __construct()
    {
        $this->userModules = new ArrayCollection();
        $this->userCertifications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGamertag(): ?string
    {
        return $this->gamertag;
    }

    public function setGamertag(string $gamertag): static
    {
        $this->gamertag = $gamertag;

        return $this;
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getPointEarn(): ?int
    {
        return $this->pointEarn;
    }

    public function setPointEarn(int $pointEarn): static
    {
        $this->pointEarn = $pointEarn;

        return $this;
    }

    /**
     * @return Collection<int, UserModule>
     */
    public function getUserModules(): Collection
    {
        return $this->userModules;
    }

    public function addUserModule(UserModule $userModule): static
    {
        if (!$this->userModules->contains($userModule)) {
            $this->userModules->add($userModule);
            $userModule->setUsr($this);
        }

        return $this;
    }

    public function removeUserModule(UserModule $userModule): static
    {
        if ($this->userModules->removeElement($userModule)) {
            // set the owning side to null (unless already changed)
            if ($userModule->getUsr() === $this) {
                $userModule->setUsr(null);
            }
        }

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
            $userCertification->setUsr($this);
        }

        return $this;
    }

    public function removeUserCertification(UserCertification $userCertification): static
    {
        if ($this->userCertifications->removeElement($userCertification)) {
            // set the owning side to null (unless already changed)
            if ($userCertification->getUsr() === $this) {
                $userCertification->setUsr(null);
            }
        }

        return $this;
    }
}
