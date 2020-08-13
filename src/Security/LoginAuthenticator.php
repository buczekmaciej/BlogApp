<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

class LoginAuthenticator extends AbstractFormLoginAuthenticator
{
    private $userRepo;
    private $router;
    private $csrf;
    private $encoder;

    public function __construct(UserRepository $uRepo, RouterInterface $router, CsrfTokenManagerInterface $csrf, UserPasswordEncoderInterface $encoder)
    {
        $this->userRepo = $uRepo;
        $this->router = $router;
        $this->csrf = $csrf;
        $this->encoder = $encoder;
    }

    public function supports(Request $request)
    {
        return $request->attributes->get("_route") == "userLogin" && $request->isMethod("POST");
    }

    public function getCredentials(Request $request)
    {
        $credentials =  [
            'username' => $request->request->get('username'),
            'password' => $request->request->get('password'),
            'csrf' => $request->request->get('csrf')
        ];

        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['username']
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf']);

        if (!$this->csrf->isTokenValid($token)) throw new InvalidCsrfTokenException();

        return $this->userRepo->findOneBy(['Username' => $credentials['username']]);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->encoder->isPasswordValid($user, $credentials['password']);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // Determine if redirected from other page or straight from nav-bar
        $link = $request->headers->get('referer');
        $elems = explode("?", $link);

        // If from other page prepare link to redirect back
        if (count($elems) == 2) {
            $res = explode("&", $elems[1]);
            $res = [explode("=", $res[0])[1], explode("=", $res[1])];
            $res = [$res[0], $res[1][0], $res[1][1]];

            return new RedirectResponse($this->router->generate($res[0], [$res[1] => $res[2]]));
        }

        return new RedirectResponse($this->router->generate("homepage"));
    }

    public function getLoginUrl()
    {
        return $this->router->generate("userLogin");
    }
}
