<?php

namespace App\Controller;

use App\Entity\ImageEntity;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Form\Type\ImageUploadType;
use App\Repository\GalleryRepository;
use Symfony\Component\Form\Util\ServerParams;

class AdminController extends AbstractController
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @Route("/admin", name="foetus_admin")
     */
    public function homeAdmin(): Response
    {

        $this->session->set('route-name', 'foetus_admin');

        // On récupère la précédente page visitée
        $previousPage = $this->session->get('route-name');

        return $this->render('admin/admin.html.twig');
    }

    /**
     * @Route("/admin/gallery/add/{type}", name="add_gallery")
     * 
     * @Route("/admin/flash/add/{type}", name="add_flash")
     */
    public function addToGallery(
        Request $request,
        GalleryRepository $galleryRepository,
        FileUploader $fileUploader,
        $type
    ): Response {

        // // Utile pour vérifier la config dans php.ini
        // $a = new ServerParams();
        // echo ini_get('post_max_size') . "\n";
        // echo $a->getPostMaxSize() . "\n";

        $manager = $this->getDoctrine()->getManager();

        $gallery = $galleryRepository->findOneBy([
            'name' => 'Gallery'
        ]);

        $form = $this->createForm(ImageUploadType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $photo = $form->get('image')->getData();

            if ($photo) {
                $photoFileName = $fileUploader->upload($photo, $type);

                $fileUploader->resize($photoFileName);
                $fileUploader->createThumb($photoFileName);

                $newImage = new ImageEntity;
                $newImage->setPathName($photoFileName);

                // Miniature
                $thumbName = str_replace("/galerie/", "/thumbs/", $photoFileName);

                $newImage->setThumbPathName($thumbName);

                $manager->persist($newImage);

                $gallery->addFile($newImage);
            }

            $manager->flush();

            $this->addFlash('success', 'Image uploadée !');
        }

        return $this->render(
            'admin/add.gallery.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }
}
