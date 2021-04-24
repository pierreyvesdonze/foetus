<?php

namespace App\Controller;

use App\Form\Type\BioType as TypeBioType;
use App\Repository\BioRepository;
use App\Service\ImageManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BioController extends AbstractController
{

    /**
     * @Route("/bio", name="foetus_bio")
     */
    public function bio(BioRepository $bioRepository, Request $request): Response
    {
        // Il n'y aura qu'une seule Bio, on la recherche donc simplement par son id : 1
        $bio = $bioRepository->findOneBy([
            'id' => 1
        ]);

        return $this->render('bio/bio.html.twig', [
            'bio' => $bio,
        ]);
    }

    /**
     * @Route("/bio/update/{type}", name="update_bio")
     */
    public function updateBio(
        Request $request,
        BioRepository $bioRepository,
        ImageManager $imageManager,
        $type
    ) {
        $bio = $bioRepository->findOneBy([
            'id' => 1
        ]);

        $form = $this->createForm(TypeBioType::class, $bio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $photo = $form->get('photoPath')->getData();
            if ($photo) {
                $photoFileName = $imageManager->upload($photo, $type);
                $imageManager->resize($photoFileName);

                $bio->setPhotoPath($photoFileName);
            }

            $manager = $this->getDoctrine()->getManager();
            $manager->flush();

            $this->addFlash('succes', 'Image uploadée');
        }

        return $this->render('bio/update.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
