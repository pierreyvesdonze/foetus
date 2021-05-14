<?php

namespace App\Controller;

use App\Entity\SocialLink;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Type\SocialType;
use App\Repository\SocialLinkRepository;
use App\Service\ImageManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;

class SocialController extends AbstractController
{
    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/social/show/admin", name="social_show")
     * 
     * @IsGranted("ROLE_ADMIN")
     * 
     * @param mixed $socialLinkRepository
     * 
     * @return Response
     */
    public function socialShow(
        SocialLinkRepository $socialLinkRepository
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        /**
         * @var SocialLink $socialLinks
         */
        $socialLinks = $socialLinkRepository->findAll();

        return $this->render('social/social.show.html.twig', [
            'socialLinks' => $socialLinks
        ]);
    }

    /**
     * @Route("/social/add/{type}/admin", name="social_add")
     * 
     * @IsGranted("ROLE_ADMIN")
     * 
     * @param string $stype
     * 
     * @return Response
     */
    public function socialAdd(
        Request $request,
        ImageManager $imageManager,
        $type
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(SocialType::class);
        $form->handleRequest($request);
        
        /**
         * @var SocialLink $newLink
         */
        $newLink = new SocialLink;

        if ($form->isSubmitted() && $form->isValid()) {

            $title = $form->get('title')->getData();
            $icon = $form->get('iconPath')->getData();
            $linkPath = $form->get('linkPath')->getData();

            $iconName = $imageManager->upload($icon, $type);

            $newLink->setTitle($title);
            $newLink->setIconPath($iconName);
            $newLink->setLinkPath($linkPath);

            $this->entityManager->persist($newLink);
            $this->entityManager->flush();

            $this->addFlash('success', 'Image uploadée');
        }
        return $this->render('social/social.add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/social/update/{id}", name="social_update",  methods={"GET","POST"})
     * 
     * @IsGranted("ROLE_ADMIN")
     * 
     * @param mixed $socialLink
     * 
     * @return Response
     */
    public function socialUpdate(
        Request $request,
        SocialLink $socialLink,
        ImageManager $imageManager
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(SocialType::class, $socialLink);
        $form->handleRequest($request);

        $oldImg = $socialLink->getIconPath();

        if ($form->isSubmitted() && $form->isValid()) {

            $title = $form->get('title')->getData();
            $icon = $form->get('iconPath')->getData();
            $linkPath = $form->get('linkPath')->getData();

            if (null !== $icon) {
                $imageManager->deleteImage($oldImg);
                $type = 'image';
                $iconName = $imageManager->upload($icon, $type);
            }

            $socialLink->setTitle($title);
            $socialLink->setIconPath($iconName);
            $socialLink->setLinkPath($linkPath);

            $this->entityManager->flush();

            $this->addFlash('success', 'Lien modifié');
        }
        return $this->render('social/social.update.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("admin/delete/social/{id}", name="delete_social", methods={"GET", "POST"}, options={"expose"=true})
     * 
     * @IsGranted("ROLE_ADMIN")
     * 
     * @param mixed $socialLink
     * 
     * @return void
     */
    public function deleteSocial(
        ImageManager $imageManager,
        SocialLink $socialLink
    ) {
        $this->entityManager->remove($socialLink);
        $this->entityManager->flush();

        $imageManager->deleteImage($socialLink->getIconPath());
        $this->addFlash('success', "Le lien a bien été supprimé !");

        return $this->redirectToRoute('social_show');
    }
}
