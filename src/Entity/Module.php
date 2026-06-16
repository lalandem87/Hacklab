<?php

namespace App\Entity;

use Symfony\Component\Serializer\Attribute\Groups;

use App\Repository\ModuleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModuleRepository::class)]
class Module
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['module:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 60, unique: true)]
    #[Groups(['module:read'])]
    private ?string $name = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[Groups(['module:read'])]
    private ?Course $course = null;

    #[Groups(['module:read'])]
    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Challenge $challenge = null;

    #[Groups(['module:read'])]
    #[ORM\ManyToOne(inversedBy: 'modules')]
    private ?Categorie $categorie = null;

    /**
     * @var Collection<int, UserModule>
     */
    #[ORM\OneToMany(targetEntity: UserModule::class, mappedBy: 'module')]
    private Collection $userModules;

    public function __construct()
    {
        $this->userModules = new ArrayCollection();
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

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): static
    {
        $this->course = $course;

        return $this;
    }

    public function getChallenge(): ?Challenge
    {
        return $this->challenge;
    }

    public function setChallenge(?Challenge $challenge): static
    {
        $this->challenge = $challenge;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;

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
            $userModule->setModule($this);
        }

        return $this;
    }

    public function removeUserModule(UserModule $userModule): static
    {
        if ($this->userModules->removeElement($userModule)) {
            // set the owning side to null (unless already changed)
            if ($userModule->getModule() === $this) {
                $userModule->setModule(null);
            }
        }

        return $this;
    }
}
