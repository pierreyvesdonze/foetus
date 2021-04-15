<?php

namespace App\Entity;

use App\Repository\GalleryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GalleryRepository::class)
 */
class Gallery
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=ImageEntity::class, mappedBy="gallery")
     */
    private $Files;

    public function __construct()
    {
        $this->Files = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|ImageEntity[]
     */
    public function getFiles(): Collection
    {
        return $this->Files;
    }

    public function addFile(ImageEntity $file): self
    {
        if (!$this->Files->contains($file)) {
            $this->Files[] = $file;
            $file->setGallery($this);
        }

        return $this;
    }

    public function removeFile(ImageEntity $file): self
    {
        if ($this->Files->removeElement($file)) {
            // set the owning side to null (unless already changed)
            if ($file->getGallery() === $this) {
                $file->setGallery(null);
            }
        }

        return $this;
    }
}
