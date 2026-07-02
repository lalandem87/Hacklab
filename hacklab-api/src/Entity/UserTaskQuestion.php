<?php

namespace App\Entity;

use App\Repository\UserTaskQuestionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: UserTaskQuestionRepository::class)]
class UserTaskQuestion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['course:read', 'user:read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userTaskQuestions')]
    private ?User $usr = null;

    #[ORM\ManyToOne(inversedBy: 'userTaskQuestions')]
    #[Groups(['user:read'])]
    private ?TaskQuestion $question = null;

    #[ORM\Column]
    #[Groups(['user:read'])]
    private ?bool $solved = null;

    #[ORM\Column(length: 80)]
    #[Groups(['user:read'])]
    private ?string $submittedAnswer = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getQuestion(): ?TaskQuestion
    {
        return $this->question;
    }

    public function setQuestion(?TaskQuestion $question): static
    {
        $this->question = $question;

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

    public function getSubmittedAnswer(): ?string
    {
        return $this->submittedAnswer;
    }

    public function setSubmittedAnswer(string $submittedAnswer): static
    {
        $this->submittedAnswer = $submittedAnswer;

        return $this;
    }
}
