<?php

namespace App\Controller;

use App\Form\RegisterType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        return $this->render('security/login.html.twig', [
            'last_username' => $authenticationUtils->getLastUsername(),
            'error' => $authenticationUtils->getLastAuthenticationError(),
        ]);
    }

    /**
     * @Route("/register", name="register")
     */
    public function register(?string $error = null, Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $encoder, UserRepository $userRepository): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        $registerForm = $this->createForm(RegisterType::class);
        $registerForm->handleRequest($request);

        if ($registerForm->isSubmitted() && $registerForm->isValid()) {
            $user = $registerForm->getData();

            if (!$userRepository->registerValidateData($user->getUsername(), $user->getEmail())) {
                $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
                $user->setRoles(['ROLE_USER']);

                $preferences = new \App\Entity\Preference;
                $user->setPreferences($preferences);

                $entityManager->persist($preferences);
                $entityManager->persist($user);

                $entityManager->flush();

                return $this->redirectToRoute('login');
            } else {
                $error = "Username or e-mail is already taken";
            }
        }

        return $this->render('security/register.html.twig', [
            'form' => $registerForm->createView(),
            'error' => $error,
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {}
}
