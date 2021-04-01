<?php

namespace App\Controller;

use App\Form\BioType;
use App\Repository\BioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BioController extends AbstractController
{
    /**
     * @Route("/bio", name="foetus_bio")
     */
    public function bio(): Response
    {
        return $this->render('bio/bio.html.twig');
    }

    /**
     * @Route("/bio/update", name="update_bio")
     */
    public function updateBio(Request $request, BioRepository $bioRepository)
    {
        // Il n'y aura qu'une seule Bio, on la recherche donc simplement par son id : 1
        $bio = $bioRepository->findOneBy([
            'id' => 1
        ]);

        $form = $this->createForm(BioType::class, $bio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();
        }

        return $this->render('bio/update.html.twig', [
            'form' => $form->createView()
        ]);

   
    }
}
