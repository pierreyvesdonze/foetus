<?php

namespace App\Controller;

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
}
