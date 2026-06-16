<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: CourseRepository::class)]
class Course
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
     #[Groups(['module:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 40, unique: true)]
     #[Groups(['module:read'])]
    private ?string $name = null;

    #[ORM\Column]
     #[Groups(['module:read'])]
    private ?int $point = null;

    /**
     * @var Collection<int, CourseImage>
     */
    #[ORM\OneToMany(targetEntity: CourseImage::class, mappedBy: 'course')]
    #[Groups(['course:read', 'module:read'])]
    private Collection $courseImages;

    /**
     * @var Collection<int, Task>
     */
    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'course')]
    #[Groups(['course:read'])]
    private Collection $task;

    public function __construct()
    {
        $this->courseImages = new ArrayCollection();
        $this->task = new ArrayCollection();
    }


    #[Groups(['course:read'])]
    public function getId(): ?int
    {
        return $this->id;
    }

    #[Groups(['course:read'])]
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPoint(): ?int
    {
        return $this->point;
    }

    public function setPoint(int $point): static
    {
        $this->point = $point;

        return $this;
    }

    /**
     * @return Collection<int, CourseImage>
     */
    
    public function getCourseImages(): Collection
    {
        return $this->courseImages;
    }

    public function addCourseImage(CourseImage $courseImage): static
    {
        if (!$this->courseImages->contains($courseImage)) {
            $this->courseImages->add($courseImage);
            $courseImage->setCourse($this);
        }

        return $this;
    }

    public function removeCourseImage(CourseImage $courseImage): static
    {
        if ($this->courseImages->removeElement($courseImage)) {
            // set the owning side to null (unless already changed)
            if ($courseImage->getCourse() === $this) {
                $courseImage->setCourse(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getTask(): Collection
    {
        return $this->task;
    }

    public function addTask(Task $task): static
    {
        if (!$this->task->contains($task)) {
            $this->task->add($task);
            $task->setCourse($this);
        }

        return $this;
    }

    public function removeTask(Task $task): static
    {
        if ($this->task->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getCourse() === $this) {
                $task->setCourse(null);
            }
        }

        return $this;
    }
}
