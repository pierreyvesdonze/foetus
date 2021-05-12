<?php

namespace App\Controller;

use App\Form\Type\ContactType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('main/index.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(
        MailerInterface $mailer,
        Request $request
    ): Response {

        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        
            $sender = $form->get('email')->getData();
            $text = $form->get('text')->getData();

            $message = (new TemplatedEmail())
                ->from('pyd3.14@gmail.com')
                ->to(
                    'pyd3.14@gmail.com',
                )
                ->subject('De la part de '.$sender.' !')
                ->htmlTemplate('email/contact.notification.html.twig')
                ->context([
                    'sender'  => $sender,
                    'text' => $text
                ]);

            $mailer->send($message);
        }
        return $this->render('email/contact.form.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
