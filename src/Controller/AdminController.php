<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="foetus_admin", options={"expose"=true})
     */
    public function homeAdmin(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if (true == $this->getUser()) {
            return $this->render('admin/admin.html.twig');

        } else {
            return $this->redirectToRoute('login');
        }
    }
}
