<?php

namespace App\Controller;

use App\Form\Type\LogoType;
use App\Service\ImageManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin", options={"expose"=true})
     * 
     * @IsGranted("ROLE_ADMIN")
     * 
     * @return Response
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

    /**
     * @Route("/admin/update/logo", name="update_logo")
     * 
     * @IsGranted("ROLE_ADMIN")
     * 
     * @return Reponse
     */
    public function updateLogo(
        Request $request,
        ImageManager $imageManager
        ): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(LogoType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photo = $form->get('logo')->getData();
            if ($photo) {
                $photoFileName = $imageManager->upload($photo, 'logo');
                $imageManager->resize($photoFileName);

            }

            $this->addFlash('success', 'Logo modifié !');
        }

        return $this->render('admin/update.logo.html.twig', [
            'form'  => $form->createView()
        ]);
    }
}
