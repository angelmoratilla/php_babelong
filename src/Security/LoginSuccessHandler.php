<?php

namespace App\Security;

use App\Entity\Admin;
use App\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    public function __construct(
        private RouterInterface $router
    ) {
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): RedirectResponse
    {
        $user = $token->getUser();

        // Redirigir según el tipo de usuario
        if ($user instanceof Admin) {
            return new RedirectResponse($this->router->generate('app_admin_home'));
        }

        if ($user instanceof User) {
            return new RedirectResponse($this->router->generate('app_user_dashboard'));
        }

        // Por defecto, ir al landing
        return new RedirectResponse($this->router->generate('app_landing'));
    }
}
