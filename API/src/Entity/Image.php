<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image
{
    #[ORM\Id]
    #[Assert\Uuid]
    #[ORM\Column]
    private string $id;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(length: 255)]
    private string $url;

    /**
     * @var Collection<int, Hit>
     */
    #[ORM\OneToMany(targetEntity: Hit::class, mappedBy: 'image')]
    private Collection $hits;

    public function __construct()
    {
        $this->hits = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return Collection<int, Hit>
     */
    public function getHits(): Collection
    {
        return $this->hits;
    }

    public function addHit(Hit $hit): static
    {
        if (!$this->hits->contains($hit)) {
            $this->hits->add($hit);
            $hit->setImage($this);
        }

        return $this;
    }

    public function removeHit(Hit $hit): static
    {
        if ($this->hits->removeElement($hit)) {
            // set the owning side to null (unless already changed)
            if ($hit->getImage() === $this) {
                $hit->setImage(null);
            }
        }

        return $this;
    }
}
