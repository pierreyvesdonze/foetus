<?php

namespace App\Controller;

use App\Entity\Rate;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Type\RateType;
use App\Repository\RateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;

class RateController extends AbstractController
{
    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/tarifs", name="rates_show")
     *
     * @param mixed $rateRepository
     * 
     * @return Response
     */
    public function showRate(RateRepository $rateRepository): Response
    {
        /**
         * @var Rate $ates
         */
        $rates = $rateRepository->findAll();

        return $this->render('rates/show.rates.html.twig', [
            'rates' => $rates
        ]);
    }

    /**
     * @Route("/tarifs/add/admin", name="rates_add")
     * 
     * @IsGranted("ROLE_ADMIN")
     * 
     * @param mixed $request
     * 
     * @return Response
     */
    public function addRates(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(RateType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            /**
             * @var Rate $rate
             */
            $newRate = new Rate;
            $newRate->setTitle($form->get('title')->getData());
            $newRate->setAmount($form->get('amount')->getData());

            if (null != $form->get('text')->getData()) {
                $newRate->setText($form->get('text')->getData());
            }

            $this->entityManager->persist($newRate);
            $this->entityManager->flush();

            $this->addFlash('success', 'Tarif ajouté');
        }

        return $this->render('rates/create.rate.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/tarifs/list/update/admin", name="list_rates_update")
     * 
     * @IsGranted("ROLE_ADMIN")
     * 
     * @param mixed $rateRepository
     * 
     * @return Response
     */
    public function listUpdateRates(
        RateRepository $rateRepository
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        /**
         * @var Rate $rates
         */
        $rates = $rateRepository->findAll();

        return $this->render('rates/list.rate.html.twig', [
            'rates' => $rates
        ]);
    }

    /**
     * @Route("/tarifs/update/admin/{id}", name="rates_update",
     * methods={"GET", "POST"},
     * requirements={"id":"\d+"})
     * 
     * @IsGranted("ROLE_ADMIN")
     * 
     * @param object $rate
     *
     * @return Response
     */
    public function updateRate(
        Rate $rate,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(RateType::class, $rate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $rate->setTitle($form->get('title')->getData());
            $rate->setAmount($form->get('amount')->getData());

            if (null != $form->get('text')->getData()) {
                $rate->setText($form->get('text')->getData());
            }

            $this->entityManager->flush();
            $this->addFlash('success', 'Tarif modifié');

            return $this->redirectToRoute('list_rates_update');
        }
        return $this->render('rates/update.rate.html.twig', [
            'form' => $form->createView(),
            'rate' => $rate
        ]);
    }

    /**
     * @Route("/tarifs/delete/admin/{id}", name="rates_delete",
     * methods={"GET", "POST"},
     * requirements={"id":"\d+"})
     * 
     * @IsGranted("ROLE_ADMIN")
     * 
     * @param object $rate
     * 
     * @return void
     */
    public function deleteRate(
        Rate $rate
    ) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $this->entityManager->remove($rate);
        $this->entityManager->flush();

        $this->addFlash('success', 'Le tarif a bien été supprimé !');

        return $this->redirectToRoute('list_rates_update');
    }
}
