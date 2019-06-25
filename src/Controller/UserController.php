<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\User;

class UserController extends AbstractController
{
    /**
     * @Route("/user/login", name="userLogin")
     */
    public function login(Request $req, SessionInterface $s)
    {
        $form=$this->createFormBuilder()
        ->add('Username', TextType::class, [
            'attr'=>[
                'class'=>'linp',
                'placeholder'=>'Username'
            ]
        ])
        ->add('Password', PasswordType::class, [
            'attr'=>[
                'class'=>'linp',
                'placeholder'=>'Password'
            ]
        ])
        ->add('Login', SubmitType::class, [
            'attr'=>[
                'class'=>'lsub'
            ]
        ])
        ->getForm();

        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid())
        {
            $data=$form->getData();

            $exist=$this->getDoctrine()->getRepository(User::class)->findOneBy(array('Login'=>$data['Username']),array());
            
            if($exist)
            {
                if(($exist->getPassword())===($data['Password']))
                {
                    $session->set('user',$exist);
                    $this->addFlash(
                        'success',
                        'You have been logged in! Welcome '.$data['Username']
                    );

                    return $this->redirectToRoute('appHomepage', []);
                }
            }
            else
            {
                $this->addFlash(
                    'danger',
                    'There is no such user'
                );
            }
        }

        return $this->render('user/login.html.twig', [
            'form'=>$form->createView()
        ]);
    }
}
