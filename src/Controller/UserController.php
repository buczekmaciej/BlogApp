<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\RegisterType;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    /**
     * @Route("/u/login", name="userLogin")
     */
    public function login(AuthenticationUtils $au)
    {
        $error = $au->getLastAuthenticationError();
        $username = $au->getLastUsername();

        return $this->render('user/login.html.twig', [
            'error' => $error,
            'username' => $username
        ]);
    }

    /**
     * @Route("/u/logout", name="userLogout")
     */
    public function logout()
    {
        throw new \Exception("Your session has ended");
    }

    /**
     * @Route("/u/register", name="userRegister")
     */
    public function register(\Symfony\Component\HttpFoundation\Request $request, \App\Repository\UserRepository $ur, \Doctrine\ORM\EntityManagerInterface $em, \Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface $encoder)
    {
        $register = $this->createForm(RegisterType::class);
        $register->handleRequest($request);

        if ($register->isSubmitted() && $register->isValid()) {
            $data = $register->getData();
            $email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);

            if (!$email) {
                $this->addFlash('danger', "Email is not valid");
                return $this->redirectToRoute('userRegister', []);
            }

            $matched = $ur->checkMatch($data['username'], $data['email']);
            if (!$matched) {
                $details = new \App\Entity\Details();

                $user = new \App\Entity\User();
                $user->setUsername($data['username']);
                $user->setPassword($encoder->encodePassword($user, $data['password']));
                $user->setEmail($data['email']);
                $user->setJoinedAt(new \DateTime());
                $user->setIsDisabled(FALSE);
                $user->setRoles(['ROLE_USER']);

                $em->persist($details);
                $em->persist($user);
                $em->flush();

                return $this->redirectToRoute('userLogin', []);
            } else {
                $this->addFlash('danger', "E-mail or username is already taken");
                return $this->redirectToRoute('userRegister', []);
            }
        }

        return $this->render('user/register.html.twig', [
            'register' => $register->createView()
        ]);
    }
}
