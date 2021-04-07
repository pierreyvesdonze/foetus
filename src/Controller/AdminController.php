<?php

namespace App\Controller;

use App\Entity\ImageEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Form\Type\ImageUploadType;
use App\Repository\GalleryRepository;
use App\Repository\ImageEntityRepository;
use App\Repository\RateRepository;
use App\Service\ImageManager;
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

        $manager = $this->getDoctrine()->getManager();

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

    /**
     * @Route("/admin/delete/{type}", name="delete_gallery")
     * 
     * @Route("/admin/delete/{type}", name="delete_flash", methods={"GET", "POST"})
     */
    public function deleteFromGaleries(
        ImageEntityRepository $imageEntityRepository,
        ImageManager $imageManager,
        string $type
    ) {
        // On set la page courante en session
        $this->session->set('route-name', 'delete_' . $type);

        $images = $imageEntityRepository->findByType($type);

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


        return $this->render(
            'admin/delete.gallery.html.twig',
            [
                'images' => $images,
                'type' => $type
            ]
        );
    }

    /**
     * @Route("/tarifs/update", name="foetus_rates_update")
     */
    public function updateRates(RateRepository $rateRepository)
    {
    }
}
