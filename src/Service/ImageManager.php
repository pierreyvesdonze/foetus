<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;

class ImageManager
{
    private $imageDirectory;
    private $galleryDirectory;
    private $flashDirectory;
    private $slugger;
    private $imagine;

    private const MAX_WIDTH = 1024;
    private const MAX_HEIGHT = 768;
    private const MAX_WIDTH_THUMB = 256;
    private const MAX_HEIGHT_THUMB = 192;

    public function __construct(
        $imageDirectory,
        $galleryDirectory,
        $flashDirectory,
        SluggerInterface $slugger
    ) {
        $this->imageDirectory = $imageDirectory;
        $this->galleryDirectory = $galleryDirectory;
        $this->flashDirectory = $flashDirectory;
        $this->slugger = $slugger;
        $this->imagine = new Imagine();
    }

    public function upload(UploadedFile $file, $type)
    {

        // Répertoire de destination des images
        $imageDirectory = null;

        if ($type === "bio") {
            
            $fileName = 'assets/images/foetus.' . $file->guessExtension();
            $imageDirectory = $this->getImageDirectory();

        } elseif ($type === 'gallery') {

            $fileName =
            'assets/images/galerie/' . uniqid() . '.' . $file->guessExtension();
            $imageDirectory = $this->getGalleryDirectory();

        } elseif ($type === 'flash') {

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

    public function createThumb(string $fileName, string $type): void
    {
        // Copie le fichier, le renomme avec la bonne extension et le colle dans le répertoire des miniatures
        $thumbSplit = explode('.', $fileName);

        if ('gallery' === $type) {
            $thumbName = str_replace("/galerie/", "/thumbs/", $thumbSplit[0]) . '.' . $thumbSplit[1];
        } else {
            $thumbName = str_replace("/flashes/", "/thumbs/", $thumbSplit[0]) . '.' . $thumbSplit[1];
        }
        copy($fileName, $thumbName);

        // Redimensionne les images en miniatures
        list($iwidth, $iheight) = getimagesize($thumbName);
        $ratio = $iwidth / $iheight;
        $width = self::MAX_WIDTH_THUMB;
        $height = self::MAX_HEIGHT_THUMB;
        if ($width / $height > $ratio) {
            $width = $height * $ratio;
        } else {
            $height = $width / $ratio;
        }

        $photo = $this->imagine->open($thumbName);
        $photo->resize(new Box($width, $height))->save($thumbName);
    }
}
