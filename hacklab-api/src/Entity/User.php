<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
   
    #[Groups(['course:read', 'user:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 25, unique: true)]
    #[Groups(['course:read', 'user:read'])]
    private ?string $gamertag = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Groups(['course:read', 'user:read'])]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column]
   
    #[Groups(['course:read', 'user:read'])]
    private ?int $pointEarn = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var Collection<int, UserModule>
     */
    #[ORM\OneToMany(targetEntity: UserModule::class, mappedBy: 'usr')]
    #[Groups(['course:read', 'user:read'])]
    private Collection $userModules;

    /**
     * @var Collection<int, UserCertification>
     */
    #[ORM\OneToMany(targetEntity: UserCertification::class, mappedBy: 'usr')]
    #[Groups(['course:read', 'user:read'])]
    private Collection $userCertifications;

    /**
     * @var Collection<int, UserTask>
     */
    #[ORM\OneToMany(targetEntity: UserTask::class, mappedBy: 'usr')]
    #[Groups(['user:read'])]
    private Collection $userTasks;

    /**
     * @var Collection<int, UserTaskQuestion>
     */
    #[ORM\OneToMany(targetEntity: UserTaskQuestion::class, mappedBy: 'usr')]
    #[Groups(['user:read'])]
    private Collection $userTaskQuestions;

    public function __construct()
    {
        $this->userModules = new ArrayCollection();
        $this->userCertifications = new ArrayCollection();
        $this->userTasks = new ArrayCollection();
        $this->userTaskQuestions = new ArrayCollection();
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

    /**
     * @return Collection<int, UserTask>
     */
    public function getUserTasks(): Collection
    {
        return $this->userTasks;
    }

    public function addUserTask(UserTask $userTask): static
    {
        if (!$this->userTasks->contains($userTask)) {
            $this->userTasks->add($userTask);
            $userTask->setUsr($this);
        }

        return $this;
    }

    public function removeUserTask(UserTask $userTask): static
    {
        if ($this->userTasks->removeElement($userTask)) {
            // set the owning side to null (unless already changed)
            if ($userTask->getUsr() === $this) {
                $userTask->setUsr(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserTaskQuestion>
     */
    public function getUserTaskQuestions(): Collection
    {
        return $this->userTaskQuestions;
    }

    public function addUserTaskQuestion(UserTaskQuestion $userTaskQuestion): static
    {
        if (!$this->userTaskQuestions->contains($userTaskQuestion)) {
            $this->userTaskQuestions->add($userTaskQuestion);
            $userTaskQuestion->setUsr($this);
        }

        return $this;
    }

    public function removeUserTaskQuestion(UserTaskQuestion $userTaskQuestion): static
    {
        if ($this->userTaskQuestions->removeElement($userTaskQuestion)) {
            // set the owning side to null (unless already changed)
            if ($userTaskQuestion->getUsr() === $this) {
                $userTaskQuestion->setUsr(null);
            }
        }

        return $this;
    }
}
