<?php

namespace App\Controller;

use App\Entity\ImageEntity;
use App\Entity\SocialLink;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Form\Type\ImageUploadType;
use App\Form\Type\SocialType;
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
        return $this->render('admin/admin.html.twig');
    }

    /**
     * @Route("/gallery/add/{type}/admin", name="add_gallery")
     * 
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
     * @Route("/delete/{type}/admin", name="delete_gallery", methods={"GET", "POST"}, options={"expose"=true})
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

    /**
     * @Route("/social/add/{type}/admin", name="foetus_social_add")
     */
    public function socialAdd(
        Request $request,
        ImageManager $imageManager,
        $type
    ) {
        $form = $this->createForm(SocialType::class);
        $form->handleRequest($request);
        $newLink = new SocialLink;

        if ($form->isSubmitted() && $form->isValid()) {

            $title = $form->get('title')->getData();
            $icon = $form->get('iconPath')->getData();

            dd($icon);
            $iconName = $imageManager->upload($icon, $type, $newLink);

            $newLink->setTitle($title);
            $newLink->setIconPath($iconName);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($newLink);
            $manager->flush();
  
            $iconName = $imageManager->upload($icon, $type, $newLink);



            //$imageManager->resize($photoFileName);

            $this->addFlash('succes', 'Image uploadée');
        }
        return $this->render('social/social.add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/tarifs/update/admin", name="foetus_rates_update")
     */
    public function updateRates(RateRepository $rateRepository)
    {
    }
}
