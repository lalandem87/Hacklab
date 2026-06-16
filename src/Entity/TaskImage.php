<?php

namespace App\Entity;

use Symfony\Component\Serializer\Attribute\Groups;

use App\Repository\TaskImageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskImageRepository::class)]
class TaskImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['course:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['course:read'])]
    private ?string $imageUrl = null;

    #[ORM\ManyToOne(inversedBy: 'taskImage')]
    private ?Task $task = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(string $imageUrl): static
    {
        $this->imageUrl = $imageUrl;

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
