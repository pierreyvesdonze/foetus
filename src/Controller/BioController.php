<?php

namespace App\Controller;

use App\Form\Type\BioType as TypeBioType;
use App\Repository\BioRepository;
use App\Service\ImageManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class BioController extends AbstractController
{
    /**
     * @Route("/bio", name="foetus_bio")
     */
    public function bio(BioRepository $bioRepository): Response
    {
        // Il n'y aura qu'une seule Bio, on la recherche    simplement par son id : 1
        $bio = $bioRepository->findOneBy([
            'id' => 1
        ]);

        return $this->render('bio/bio.html.twig', [
            'bio' => $bio,
        ]);
    }

    /**
     * @Route("/bio/update/{type}", name="update_bio")
     * 
     * @IsGranted("ROLE_ADMIN")
     */
    public function updateBio(
        Request $request,
        BioRepository $bioRepository,
        ImageManager $imageManager,
        $type
    ) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

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

        }
        $this->addFlash('success', 'Bio modifiée !');

        return $this->render('bio/update.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
