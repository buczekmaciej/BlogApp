<?php

namespace App\Controller;

use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    private $userRespository;
    private $entityManager;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->userRespository = $userRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/profile/{username}", name="userProfile", defaults={"username": ""})
     */
    public function profile(string $username): Response
    {
        if ($username) {
            if ($this->getUser() && $username === $this->getUser()->getUsername()) {
                return $this->redirectToRoute('userProfile');
            }

            $user = $this->userRespository->findOneBy(['username' => $username]);

            if (!$user) {
                return $this->redirectToRoute('userProfile');
            }

            return $this->render('user/profile.html.twig', [
                'location' => $user->getUsername() . "'s profile",
                'path' => 'Profile',
                'pathLink' => 'userProfile',
                'user' => $user,
            ]);
        } else if (!$username && !$this->getUser()) {
            return $this->redirectToRoute('home');
        }

        return $this->render('user/profile.html.twig', [
            'location' => $this->getUser()->getUsername() . "'s profile",
            'path' => 'Profile',
            'pathLink' => 'userProfile',
        ]);
    }

    /**
     * @Route("/edit", name="userProfileEdit")
     */
    public function edit(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $form = $this->createForm(UserType::class, null, ['id' => $this->getUser()->getId()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $usr = $this->userRespository->findOneBy(['id' => $this->getUser()->getId()]);

            if ($data->getPassword() !== "") {
                $usr->setPassword($encoder->encodePassword($usr, $data->getPassword()));
            }
            $usr->setEmail($data->getEmail());

            $this->entityManager->flush();

            return $this->redirectToRoute('userProfile');
        }

        return $this->render('user/edit.html.twig', [
            'location' => "Profile update",
            'path' => 'Profile',
            'pathLink' => 'userProfile',
            'form' => $form->createView(),
        ]);
    }
}
