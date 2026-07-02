<?php

namespace App\Entity;

use App\Repository\TaskQuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: TaskQuestionRepository::class)]
class TaskQuestion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['course:read', 'module:read', 'user:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 80)]
    #[Groups(['course:read', 'module:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 80)]
    private ?string $answer = null;

    #[ORM\Column]
    #[Groups(['course:read', 'module:read'])]
    private ?int $questionOrder = null;

    #[ORM\ManyToOne(inversedBy: 'taskQuestions')]
    private ?Task $task = null;

    /**
     * @var Collection<int, UserTaskQuestion>
     */
    #[ORM\OneToMany(targetEntity: UserTaskQuestion::class, mappedBy: 'question')]
    private Collection $userTaskQuestions;

    public function __construct()
    {
        $this->userTaskQuestions = new ArrayCollection();
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

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(string $answer): static
    {
        $this->answer = $answer;

        return $this;
    }

    public function getQuestionOrder(): ?int
    {
        return $this->questionOrder;
    }

    public function setQuestionOrder(int $questionOrder): static
    {
        $this->questionOrder = $questionOrder;

        return $this;
    }

    public function getTask(): ?Task
    {
        return $this->task;
    }

    public function setTask(?Task $task): static
    {
        $this->task = $task;

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
            $userTaskQuestion->setQuestion($this);
        }

        return $this;
    }

    public function removeUserTaskQuestion(UserTaskQuestion $userTaskQuestion): static
    {
        if ($this->userTaskQuestions->removeElement($userTaskQuestion)) {
            // set the owning side to null (unless already changed)
            if ($userTaskQuestion->getQuestion() === $this) {
                $userTaskQuestion->setQuestion(null);
            }
        }

        return $this;
    }
}
