<?php

namespace App\Controller;

use App\Repository\ImageEntityRepository;
use App\Repository\RateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class FoetusController extends AbstractController
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @Route("/", name="foetus_home")
     */
    public function index(): Response
    {
        return $this->render('foetus/index.html.twig');
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
     * @Route("/tarifs", name="foetus_rates")
     */
    public function showRate(RateRepository $rateRepository) {

        $rates = $rateRepository->findAll();

        return $this->render('rates/show.rates.html.twig', [
            'rates' => $rates
        ]);
    }
}
