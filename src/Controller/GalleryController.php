<?php

namespace App\Controller;

use App\Entity\ImageEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Type\ImageUploadType;
use App\Repository\GalleryRepository;
use App\Repository\ImageEntityRepository;
use App\Service\ImageManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Util\ServerParams;

class GalleryController extends AbstractController
{
    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
        )
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/galeries/{type}", name="foetus_galeries")
     * 
     */
    public function galeries(ImageEntityRepository $imageEntityRepository, string $type): Response
    {
        $images = $imageEntityRepository->findByType($type);

        return $this->render('galerie/galerie.html.twig', [
            'images' => $images,
        ]);
    }

    /**
     * @Route("/tattoo/add/{type}/admin", name="add_tattoo")
     * @Route("/gallery/add/{type}/admin", name="add_gallery")
     * @Route("/flash/add/{type}/admin", name="add_flash")
     */
    public function addToGaleries(
        Request $request,
        GalleryRepository $galleryRepository,
        ImageManager $imageManager,
        $type
    ): Response {

        // // Utile pour vérifier la config dans php.ini
        // $a = new ServerParams();
        // echo ini_get('post_max_size') . "\n";
        // echo $a->getPostMaxSize() . "\n";

        $gallery = $galleryRepository->findOneBy([
            'name' => 'Gallery'
        ]);

        $form = $this->createForm(ImageUploadType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $photo = $form->get('image')->getData();

            if ($photo) {
                $photoFileName = $imageManager->upload($photo, $type);

                $imageManager->resize($photoFileName);
                $imageManager->createThumb($photoFileName, $type);

                $newImage = new ImageEntity;
                $newImage->setPathName($photoFileName);

                // Miniatures
                if ('galerie' === $type) {
                    $thumbName = str_replace("/galerie/", "/thumbs/", $photoFileName);
                } else {
                    $thumbName = str_replace("/flashes/", "/thumbs/", $photoFileName);
                }

                $newImage->setThumbPathName($thumbName);

                $this->entityManager->persist($newImage);

                $gallery->addFile($newImage);
            }

            $this->entityManager->flush();

            $this->addFlash('success', 'Image uploadée !');
        }

        return $this->render(
            'admin/add.gallery.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/delete/{type}/admin", name="delete_gallery", methods={"GET", "POST"}, options={"expose"=true})
     * 
     * @Route("/delete/{type}/admin", name="delete_tattoo", methods={"GET", "POST"}, options={"expose"=true})
     * 
     * @Route("/delete/{type}/admin", name="delete_flash", methods={"GET", "POST"}, options={"expose"=true})
     */
    public function deleteFromGaleries(
        ImageEntityRepository $imageEntityRepository,
        ImageManager $imageManager,
        Request $request,
        string $type
    ) {
        $images = $imageEntityRepository->findByType($type);

        if ($request->isMethod('POST')) {

            if (isset($_POST['deleteImg'])) {

                $imgToDelete = $imageEntityRepository->findOneBy(
                    [
                        'id' => $_POST['deleteImg']
                    ]
                );

                $manager = $this->getDoctrine()->getManager();
                $manager->remove($imgToDelete);
                $manager->flush();
                $imageManager->deleteImage($imgToDelete->getThumbPathName());
                $imageManager->deleteImage($imgToDelete->getPathName());
                $this->addFlash('success', "L'image a bien été supprimée !");
            }

            return $this->json('ok');
        }

        return $this->render(
            'admin/delete.gallery.html.twig',
            [
                'images' => $images,
                'type' => $type
            ]
        );
    }
}