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
use App\Entity\Articles;
use App\Entity\Comments;
use App\Entity\Details;
use Doctrine\ORM\EntityManagerInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/user/login", name="userLogin")
     */
    public function login(Request $req, SessionInterface $session)
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

            $exist=$this->getDoctrine()->getRepository(User::class)->findOneBy(array('Login'=>$data['Username']),array());
            
            if($exist)
            {
                if(($exist->getPassword())===($data['Password']) && $exist->getIsDisabled() === false)
                {
                    $session->set('user',$exist);
                    $this->addFlash(
                        'success',
                        'You have been logged in! Welcome '.$data['Username']
                    );

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
    public function register(Request $req, EntityManagerInterface $em, SessionInterface $session)
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
                    $detail=new Details();

                    $user=new User();
                    $user->setLogin($data['Username']);
                    $user->setPassword($data['Password']);
                    $user->setEmail($data['Email']);
                    $user->setJoinedAt(new \DateTime());
                    $user->setDetails($detail);


                    $em->persist($user);
                    $em->flush();

                    $this->addFlash('success', 'You were registered');

                    return $this->redirectToRoute('userLogin', []);
                }
                else
                {
                    $this->addFlash('danger','E-mail is already taken');
                }
            }
            else if($data['Username']==='admin'||'Admin')
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
    public function userProfile($user, SessionInterface $session)
    {
        $session=$session->get('user');
        if(!$session)
        {
            return $this->redirectToRoute('userLogin', []);
        }
        $user=$this->getDoctrine()->getRepository(User::class)->findBy(['Login'=>$user]);

        $details=$user[0]->getDetails();

        $comments=$this->getDoctrine()->getRepository(Comments::class)->findBy(['User'=>$user]);
        $posts=$this->getDoctrine()->getRepository(Articles::class)->findBy(['user'=>$user]);
        
        return $this->render('user/profile.html.twig', [
            'name'=>$session->getLogin(),
            'user'=>$user,
            'logged'=>$session,
            'details'=>$details,
            'posts'=>$posts,
            'comments'=>$comments
        ]);
    }

    /**
     * @Route("/{user}/profile", name="userEditProfile")
     */
    public function userEditProfile($user, SessionInterface $session, Request $request, EntityManagerInterface $em)
    {
        $logged=$session->get('user');
        if($user != $logged->getLogin())
        {
            return $this->redirectToRoute('userEditProfile', [
                'user'=>$logged->getLogin()
            ]);
        }

        $user=$this->getDoctrine()->getRepository(User::class)->findBy(['Login'=>$user]);

        $details=$user[0]->getDetails();

        $date=$details->getBirthdayDate();
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
                'value'=>$details->getFirstName()
            ]
        ])
        ->add('Bday',DateType::class, [
            'attr'=>[
                'class'=>'value'
            ],
            'format'=>'dd/MM/yyyy',
            'days'=>range(1,31),
            'months'=>range(1,12),
            'years'=>range(date('Y')-100, date('Y')),
            'data'=>new \DateTime($date)
        ])
        ->add('Location',TextType::class, [
            'attr'=>[
                'class'=>'value',
                'value'=>$details->getLocation()
            ]
        ])
        ->add('Bio',TextareaType::class, [
            'attr'=>[
                'class'=>'value'
            ],
            'data'=>$details->getBio()
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

            dump($data['Bday']);
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
            'name'=>$logged->getLogin(),
            'user'=>$logged,
            'details'=>$details,
            'form'=>$form->createView()
        ]);
    }
}
