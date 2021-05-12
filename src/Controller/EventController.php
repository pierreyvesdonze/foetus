<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\Type\EventType;
use App\Repository\EventRepository;
use App\Service\ImageManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;

class EventController extends AbstractController
{
    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/event", name="events")
     */
    public function events(EventRepository $eventRepository): Response
    {
        
        //$events = $eventRepository->findAll();
        $events = $eventRepository->findBy(
            [],
            ['date' => 'DESC']
        );

        return $this->render('event/events.html.twig', [
            'events' => $events,
        ]);
    }

    /**
     * @Route("/event/add", name="add_event")
     * 
     * @IsGranted("ROLE_ADMIN")
     */
    public function eventAdd(
        ImageManager $imageManager,
        Request $request
        ): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(EventType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newEvent = new Event;
            $newEvent->setTitle($form->get('title')->getData());
            $newEvent->setText($form->get('text')->getData());
            $newEvent->setDate($form->get('date')->getData());

            $eventImage = $form->get('image')->getData();

            if ($eventImage) {
                $imgName = $imageManager->upload($eventImage, 'event');
                $imageManager->resize($imgName);

                $newEvent->setImage($imgName);
            }
            $this->entityManager->persist($newEvent);
            $this->entityManager->flush();

            $this->addFlash('success', 'Actu ajoutée !');
        }

        return $this->render('event/event.add.html.twig',
        [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/events/list/update/admin", name="list_events_update")
     * 
     * @IsGranted("ROLE_ADMIN")
     */
    public function listUpdateEvents(EventRepository $eventRepository)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $events = $eventRepository->findAll();

        return $this->render('event/event.list.update.html.twig', [
            'events' => $events
        ]);
    }

    /**
     * @Route("/event/update/{id}", name="update_event")
     * 
     * @IsGranted("ROLE_ADMIN")
     */
    public function eventUpdate(
        ImageManager $imageManager,
        Request $request,
        Event $event
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $event->setTitle($form->get('title')->getData());
            $event->setText($form->get('text')->getData());
            $event->setDate($form->get('date')->getData());

            $eventImage = $form->get('image')->getData();

            if ($eventImage) {
                $imgName = $imageManager->upload($eventImage, 'event');
                $imageManager->resize($imgName);

                $event->setImage($imgName);
            }
   
            $this->entityManager->flush();

            $this->addFlash('success', 'Actu modifiée !');

            return $this->redirectToRoute('event_show', [
                'id' => $event->getId()
            ]);
        }

        return $this->render('event/event.update.html.twig', [
            'form' => $form->createView(),
            'event' => $event
        ]);
    }

    /**
     * @Route("/events/delete/admin/{id}", name="event_delete",
     * methods={"GET", "POST"},
     * requirements={"id":"\d+"})
     * 
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteEvent(
        Event $event
    ) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $this->entityManager->remove($event);
        $this->entityManager->flush();

        $this->addFlash('success', "L'actu a bien été supprimée");

        return $this->redirectToRoute('list_events_update');
    }

    /**
     * @Route("/events/show/{id}", name="event_show")
     */
    public function eventShow(Event $event): Response
    {
        return $this->render('event/event.show.html.twig', [
            'event' => $event
        ]);
    }
}