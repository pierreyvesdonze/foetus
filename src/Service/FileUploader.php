<?php

// src/Service/FileUploader.php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;

class FileUploader
{
    private $imageDirectory;
    private $galleryDirectory;
    private $slugger;
    private $imagine;

    private const MAX_WIDTH = 1024;
    private const MAX_HEIGHT = 768;

    public function __construct(
        $imageDirectory,
        $galleryDirectory,
        SluggerInterface $slugger
    ) {
        $this->imageDirectory = $imageDirectory;
        $this->galleryDirectory = $galleryDirectory;
        $this->slugger = $slugger;
        $this->imagine = new Imagine();
    }

    public function upload(UploadedFile $file, $type)
    {
        // Répertoire de destination des images
        $imageDirectory = null;

        if ($type === "bio") {
            
            $fileName = 'assets/images/foetus.'.$file->guessExtension();
            $imageDirectory = $this->getImageDirectory();

        } elseif ($type === 'gallery') {

            $fileName =
            'assets/images/galerie/' . uniqid() . '.' . $file->guessExtension();
            $imageDirectory = $this->getGalleryDirectory();
        } else {

            $fileName =
                'assets/images/flashes/' . uniqid() . '.' . $file->guessExtension();
            $imageDirectory = $this->getFlashDirectory();
        }

        try {
            $file->move($imageDirectory, $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    public function getImageDirectory()
    {
        return $this->imageDirectory;
    }

    public function getGalleryDirectory()
    {
        return $this->galleryDirectory;
    }

    public function getFlashDirectory()
    {
        return $this->flashDirectory;
    }

    public function resize(string $filename): void
    {
        list($iwidth, $iheight) = getimagesize($filename);
        $ratio = $iwidth / $iheight;
        $width = self::MAX_WIDTH;
        $height = self::MAX_HEIGHT;
        if ($width / $height > $ratio) {
            $width = $height * $ratio;
        } else {
            $height = $width / $ratio;
        }

        $photo = $this->imagine->open($filename);
        $photo->resize(new Box($width, $height))->save($filename);
    }
}
