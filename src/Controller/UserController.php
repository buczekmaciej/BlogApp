<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="userProfile")
     */
    public function profile(): Response
    {
        return $this->render('user/profile.html.twig', [
            'location' => $this->getUser()->getUsername() . "'s profile",
            'path' => 'Profile',
            'pathLink' => 'userProfile',
        ]);
    }
}
