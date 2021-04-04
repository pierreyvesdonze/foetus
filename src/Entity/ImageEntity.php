<?php

namespace App\Entity;

use App\Repository\ImageEntityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ImageEntityRepository::class)
 */
class ImageEntity
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
    private $pathName;

    /**
     * @ORM\ManyToOne(targetEntity=Gallery::class, inversedBy="Files")
     * @ORM\JoinColumn(nullable=false)
     */
    private $gallery;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $thumbPathName;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPathName(): ?string
    {
        return $this->pathName;
    }

    public function setPathName(string $pathName): self
    {
        $this->pathName = $pathName;

        return $this;
    }

    public function getGallery(): ?Gallery
    {
        return $this->gallery;
    }

    public function setGallery(?Gallery $gallery): self
    {
        $this->gallery = $gallery;

        return $this;
    }

    public function getThumbPathName(): ?string
    {
        return $this->thumbPathName;
    }

    public function setThumbPathName(string $thumbPathName): self
    {
        $this->thumbPathName = $thumbPathName;

        return $this;
    }
}
