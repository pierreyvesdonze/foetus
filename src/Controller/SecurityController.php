<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class SecurityController extends AbstractController
{
    public function index()
    {
        $projectDir = $this->getParameter('kernel.project_dir');
        $adminEmail = $this->getParameter('app.admin_email');
    }

    /**
     * @Route("/login", name="login")
     * 
     * @param  mixed $authenticationUtils
     * 
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('admin');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="logout")
     * 
     * @return void
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }

    /**
     * @Route("/reset-pwd", name="reset_pwd")
     * 
     * @IsGranted("ROLE_ADMIN")
     * 
     * @param  mixed $request
     * @param  mixed $passwordEncoder
     * 
     * @return void
     */
    public function resetPwd(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($request->isMethod('POST')) {
            $data = $request->getContent();
            $data = json_decode($data);
            $token = $data->token;
            $password = $data->password;

            $entityManager = $this->getDoctrine()->getManager();
            $user = $this->getUser();

            $user->setPassword($passwordEncoder->encodePassword($user, $password));
            $entityManager->flush();

            return $this->json([
                'result' => 'mot de passe modifi??',
            ]);
        } else {
            return $this->json([
                'text' => 'modification impossible',
                'result' => false,
            ], 400);
        }
    }
}
