<?php

namespace App\Controller;

use App\Form\BioType;
use App\Repository\BioRepository;
use App\Service\FileUploader;
use App\Service\ImageOptimizer;
use App\Service\NavSession;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class BioController extends AbstractController
{
    private $session;

    public function __construct(RequestStack $requestStack, SessionInterface $session)
    {
        
        $this->session = $session;
    }

    /**
     * @Route("/bio", name="foetus_bio")
     */
    public function bio(BioRepository $bioRepository, Request $request): Response
    {

        // On récupère la précédente page visitée
        $previousPage = $request->headers->get('referer');

        // Il n'y aura qu'une seule Bio, on la recherche donc simplement par son id : 1
        $bio = $bioRepository->findOneBy([
            'id' => 1
        ]);

        return $this->render('bio/bio.html.twig', [
            'bio' => $bio,
            'previousPage' => $previousPage
        ]);
    }

    /**
     * @Route("/bio/update", name="update_bio")
     */
    public function updateBio(Request $request, BioRepository $bioRepository, FileUploader $fileUploader, ImageOptimizer $imageOptimizer)
    {
        // On récupère la précédente page visitée
        $previousPage = $request->headers->get('referer');

        $bio = $bioRepository->findOneBy([
            'id' => 1
        ]);

        $form = $this->createForm(BioType::class, $bio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $photo = $form->get('photoPath')->getData();
            if ($photo) {
                $photoFileName = $fileUploader->upload($photo);
                $fileUploader->resize($photoFileName);

                $bio->setPhotoPath($photoFileName);
            }
        
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();
        }

        return $this->render('bio/update.html.twig', [
            'form' => $form->createView(),
            'previousPage' => $previousPage
        ]);
    }
}
