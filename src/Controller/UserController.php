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
use Doctrine\ORM\EntityManagerInterface;

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

    /**
     * @Route("/user/register", name="userRegister")
     */
    public function register(Request $req, EntityManagerInterface $em)
    {
        $form=$this->createFormBuilder()
        ->add('Username', TextType::class, [
            'attr'=>[
                'class'=>'rinp',
                'placeholder'=>'Username'
            ]
        ])
        ->add('Password', RepeatedType::class, [
            'type'=>PasswordType::class,
            'invalid_message'=>'Passwords must be the same',
            'options'=>['attr'=>['class'=>'rinp']],
            'first_options'=>['attr'=>['placeholder'=>'Password']],
            'second_options'=>['attr'=>['placeholder'=>'Repeat password']]
        ])
        ->add('Email',TextType::class, [
            'attr'=>[
                'class'=>'rinp',
                'placeholder'=>'E-mail'
            ]
        ])
        ->add('Submit', SubmitType::class, [
            'attr'=>[
                'class'=>'rsub'
            ]
        ])
        ->getForm();

        $form->handleRequest($req);

        if($form->isSubmitted() && $form->isValid())
        {
            $data=$form->getData();

            $exist=$this->getDoctrine()->getRepository(User::class)->findOneBy(array('Login'=>$data['Username']),array());
            if(!$exist)
            {
                $taken=$this->getDoctrine()->getRepository(User::class)->findOneBy(array('Email'=>$data['Email']),array());
                if(!$taken)
                {
                    $user=new User();
                    $user->setLogin($data['Username']);
                    $user->setPassword($data['Password']);
                    $user->setEmail($data['Email']);

                    $em->persist($user);
                    $em->flush();

                    return $this->redirectToRoute('userLogin', []);
                }
                else
                {
                    $this->addFlash('danger','E-mail is already taken');
                }
            }
            else
            {
                $this->addFlash('danger','User already exist');
            }
        }

        return $this->render('user/register.html.twig', [
            'form'=>$form->createView()
        ]);
    }
}
