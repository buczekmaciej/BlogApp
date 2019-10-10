<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\CommentsRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/user/login", name="userLogin")
     */
    public function login(Request $req, SessionInterface $session, UserRepository $uR)
    {
        $logged=$session->get('user');
        if($logged)
        {
            return $this->redirectToRoute('appHomepage');
        }

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

            $exist=$uR->findBy(['Login'=>$data['Username']]);
            
            if($exist)
            {
                if(($exist->getPassword())===($data['Password']) && $exist->getIsDisabled() === false)
                {
                    $session->set('user',$exist[0]);

                    return $this->redirectToRoute('appHomepage', []);
                }
                else
                {
                    $this->addFlash('danger', 'Password is wrong or account has been disabled');
                    return $this->redirectToRoute('userLogin', []);
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
    public function register(Request $req, EntityManagerInterface $em, UserRepository $uR, SessionInterface $session)
    {
        $logged=$session->get('user');
        if($logged)
        {
            return $this->redirectToRoute('appHomepage');
        }

        $form=$this->createFormBuilder()
        ->add('Username', TextType::class, [
            'attr'=>[
                'class'=>'rinp',
                'placeholder'=>'Username'
            ]
        ])
        ->add('Password', RepeatedType::class, [
            'type'=>PasswordType::class,
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

            $exist=$uR->findBy(['Login'=>$data['Username']]);
            if(!$exist)
            {
                $taken=$uR->findBy(['Email'=>$data['Email']]);
                if(!$taken)
                {
                    $detail=new Details();

                    $user=new User();
                    $user->setLogin($data['Username']);
                    $user->setPassword($data['Password']);
                    $user->setEmail($data['Email']);
                    $user->setJoinedAt(new \DateTime());
                    $user->setDetails($detail);


                    $em->persist($user);
                    $em->flush();

                    return $this->redirectToRoute('userLogin', []);
                }
                else
                {
                    $this->addFlash('danger','E-mail is already taken');
                }
            }
            else if(strtoupper($data['Username']) === "ADMIN")
            {
                $this->addFlash('danger','That is restricted');
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

    /**
     * @Route("/user/logout", name="userLogout")
     */
    public function logout(SessionInterface $session)
    {
        $session->clear();

        return $this->redirectToRoute('userLogin', []);
    }

    /**
     * @Route("/{user}", name="userProfile")
     */
    public function userProfile($user, SessionInterface $session, UserRepository $uR, CommentsRepository $cR)
    {
        $logged=$session->get('user');
        if(!$logged)
        {
            return $this->redirectToRoute('userLogin', []);
        }
        if($user !== $logged->getLogin())
        {
            return $this->redirectToRoute('userProfile', ['user'=>$logged->getLogin()]);
        }

        $user=$uR->findBy(['Login'=>$user])[0];
        $comments=$cR->findBy(['User'=>$user]);
        
        return $this->render('user/profile.html.twig', [
            'user'=>$user,
            'comments'=>$comments
        ]);
    }

    /**
     * @Route("/{user}/profile", name="userEditProfile")
     */
    public function userEditProfile($user, SessionInterface $session, Request $request, EntityManagerInterface $em, UserRepository $uR)
    {
        $logged=$session->get('user');
        if($user != $logged->getLogin())
        {
            return $this->redirectToRoute('userEditProfile', [
                'user'=>$logged->getLogin()
            ]);
        }

        $user=$uR->findBy(['Login'=>$user]);

        $date=$user[0]->getDetails()->getBirthdayDate();
        if($date)
        {
            $date=$date->format('Y-m-d');
        }

        $form=$this->createFormBuilder()
        ->add('Email',TextType::class, [
            'attr'=>[
                'class'=>'value',
                'value'=>$logged->getEmail()
            ]
        ])
        ->add('firstName',TextType::class, [
            'attr'=>[
                'class'=>'value',
                'value'=>$user[0]->getDetails()->getFirstName()
            ]
        ])
        ->add('Bday',DateType::class, [
            'attr'=>[
                'class'=>'value'
            ],
            'format'=>'dd/MM/yyyy',
            'years'=>range(date('Y')-100, date('Y')),
            'data'=>new \DateTime($date)
        ])
        ->add('Location',TextType::class, [
            'attr'=>[
                'class'=>'value',
                'value'=>$user[0]->getDetails()->getLocation()
            ]
        ])
        ->add('Bio',TextareaType::class, [
            'attr'=>[
                'class'=>'value'
            ],
            'data'=>$user[0]->getDetails()->getBio()
        ])
        ->add('Save', SubmitType::class, [
            'attr'=>[
                'class'=>'btn btn-submit'
            ]
        ])
        ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $data=$form->getData();

            $logged->setEmail($data['Email']);
            $details->setFirstName($data['firstName']);
            $details->setBirthdayDate($data['Bday']);
            $details->setLocation($data['Location']);
            $details->setBio($data['Bio']);

            $em->flush();
            
            return $this->redirectToRoute('userProfile', [
                'user'=>$logged->getLogin()
            ]);
        }

        return $this->render('user/profileEdit.html.twig', [
            'user'=>$user[0],
            'form'=>$form->createView()
        ]);
    }
}
