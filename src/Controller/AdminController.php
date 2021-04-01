<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

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
}