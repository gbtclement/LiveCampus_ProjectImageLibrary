<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image
{
    #[ORM\Id]
    #[ORM\Column(length: 32)]
    private string $id;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(length: 255)]
    private string $url;

    /**
     * @var Collection<int, Hit>
     */
    #[ORM\OneToMany(targetEntity: Hit::class, mappedBy: 'image', cascade: ['persist', 'remove'], fetch:'EAGER')]
    private Collection $hits;

    public function __construct()
    {
        $this->hits = new ArrayCollection();
        $this->id = Uuid::v4()->toRfc4122();
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
