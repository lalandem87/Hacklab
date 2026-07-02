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
    #[Groups(['course:read', 'module:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    #[Groups(['course:read', 'module:read'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['course:read', 'module:read'])]
    private ?string $content = null;

    #[ORM\Column]
    #[Groups(['course:read', 'module:read'])]
    private ?int $taskOrder = null;

    #[ORM\ManyToOne(inversedBy: 'Task')]
    private ?Course $course = null;

    /**
     * @var Collection<int, taskImage>
     */
    #[ORM\OneToMany(targetEntity: taskImage::class, mappedBy: 'task')]
    #[Groups(['course:read', 'module:read'])]
    private Collection $taskImages;

    /**
     * @var Collection<int, TaskQuestion>
     */
    #[ORM\OneToMany(targetEntity: TaskQuestion::class, mappedBy: 'task')]
    #[Groups(['course:read', 'module:read'])]
    private Collection $taskQuestions;

    /**
     * @var Collection<int, UserTask>
     */
    #[ORM\OneToMany(targetEntity: UserTask::class, mappedBy: 'task')]
    private Collection $userTasks;

    /**
     * @var Collection<int, UserTaskQuestion>
     */
    #[ORM\OneToMany(targetEntity: UserTaskQuestion::class, mappedBy: 'task')]
    private Collection $userTaskQuestions;

    public function __construct()
    {
        $this->taskImages = new ArrayCollection();
        $this->taskQuestions = new ArrayCollection();
        $this->userTasks = new ArrayCollection();
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

    /**
     * @return Collection<int, TaskQuestion>
     */
    public function getTaskQuestions(): Collection
    {
        return $this->taskQuestions;
    }

    public function addTaskQuestion(TaskQuestion $taskQuestion): static
    {
        if (!$this->taskQuestions->contains($taskQuestion)) {
            $this->taskQuestions->add($taskQuestion);
            $taskQuestion->setTask($this);
        }

        return $this;
    }

    public function removeTaskQuestion(TaskQuestion $taskQuestion): static
    {
        if ($this->taskQuestions->removeElement($taskQuestion)) {
            // set the owning side to null (unless already changed)
            if ($taskQuestion->getTask() === $this) {
                $taskQuestion->setTask(null);
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
            $userTask->setTask($this);
        }

        return $this;
    }

    public function removeUserTask(UserTask $userTask): static
    {
        if ($this->userTasks->removeElement($userTask)) {
            // set the owning side to null (unless already changed)
            if ($userTask->getTask() === $this) {
                $userTask->setTask(null);
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
            $userTaskQuestion->setTask($this);
        }

        return $this;
    }

    public function removeUserTaskQuestion(UserTaskQuestion $userTaskQuestion): static
    {
        if ($this->userTaskQuestions->removeElement($userTaskQuestion)) {
            // set the owning side to null (unless already changed)
            if ($userTaskQuestion->getTask() === $this) {
                $userTaskQuestion->setTask(null);
            }
        }

        return $this;
    }
}
