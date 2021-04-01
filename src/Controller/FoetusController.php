<?php

namespace App\Controller;

use App\Repository\ImageEntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FoetusController extends AbstractController
{
    /**
     * @Route("/", name="foetus_home")
     */
    public function index(): Response
    {
        return $this->render('foetus/index.html.twig');
    }

    /**
     * @Route("/galerie", name="foetus_galerie")
     */
    public function galerie(ImageEntityRepository $imageEntityRepository): Response
    {
        $images = $imageEntityRepository->findAll();

        return $this->render('galerie/galerie.html.twig', [
            'images' => $images
        ]);
    }
}
