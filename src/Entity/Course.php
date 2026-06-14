<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CourseRepository::class)]
class Course
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 40, unique: true)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column]
    private ?int $point = null;

    /**
     * @var Collection<int, CourseImage>
     */
    #[ORM\OneToMany(targetEntity: CourseImage::class, mappedBy: 'course')]
    private Collection $courseImages;

    public function __construct()
    {
        $this->courseImages = new ArrayCollection();
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
}
