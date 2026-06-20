<?php

namespace App\Entity;

use App\Repository\TaskQuestionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: TaskQuestionRepository::class)]
class TaskQuestion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['course:read', 'module:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 80)]
    #[Groups(['course:read', 'module:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 80)]
    #[Groups(['course:read', 'module:read'])]
    private ?string $answer = null;

    #[ORM\Column]
    #[Groups(['course:read', 'module:read'])]
    private ?int $questionOrder = null;

    #[ORM\ManyToOne(inversedBy: 'taskQuestions')]
    private ?Task $task = null;

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
}
