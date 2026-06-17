<?php

namespace App\Entity;

use Symfony\Component\Serializer\Attribute\Groups;

use App\Repository\ChallengeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChallengeRepository::class)]
class Challenge
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['module:read', 'user:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 40, unique: true)]
    #[Groups(['module:read', 'user:read'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['module:read', 'user:read'])]
    private ?string $content = null;

    #[ORM\Column]
    #[Groups(['module:read', 'user:read'])]
    private ?int $point = null;

    #[ORM\Column(length: 255)]
    private ?string $flag = null;

    #[Groups(['module:read', 'user:read'])]
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

    public function getFlag(): ?string
    {
        return $this->flag;
    }

    public function setFlag(string $flag): static
    {
        $this->flag = $flag;

        return $this;
    }
}
