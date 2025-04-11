<?php

namespace App\Entity;

use App\Repository\HitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HitRepository::class)]
class Hit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Image::class, cascade: ['persist', 'remove'], fetch:'EAGER', inversedBy: 'hits')]
    #[ORM\JoinColumn(name: 'image_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private ?Image $image = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image)
    {
        $this->image = $image;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
