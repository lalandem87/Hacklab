<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Attribute\Groups;

use App\Repository\TaskRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['course:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    #[Groups(['course:read'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['course:read'])]
    private ?string $content = null;

    #[ORM\Column]
    #[Groups(['course:read'])]
    private ?int $taskOrder = null;

    #[ORM\ManyToOne(inversedBy: 'Task')]
    private ?Course $course = null;

    /**
     * @var Collection<int, taskImage>
     */
    #[ORM\OneToMany(targetEntity: taskImage::class, mappedBy: 'task')]
    #[Groups(['course:read'])]
    private Collection $taskImages;

    public function __construct()
    {
        $this->taskImages = new ArrayCollection();
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getTaskOrder(): ?int
    {
        return $this->taskOrder;
    }

    public function setTaskOrder(int $taskOrder): static
    {
        $this->taskOrder = $taskOrder;

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

    /**
     * @return Collection<int, taskImage>
     */
    public function getTaskImages(): Collection
    {
        return $this->taskImages;
    }

    public function addTaskImage(taskImage $taskImage): static
    {
        if (!$this->taskImages->contains($taskImage)) {
            $this->taskImages->add($taskImage);
            $taskImage->setTask($this);
        }

        return $this;
    }

    public function removeTaskImage(taskImage $taskImage): static
    {
        if ($this->taskImages->removeElement($taskImage)) {
            // set the owning side to null (unless already changed)
            if ($taskImage->getTask() === $this) {
                $taskImage->setTask(null);
            }
        }

        return $this;
    }
}
