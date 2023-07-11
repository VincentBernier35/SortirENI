<?php

namespace App\Security;

use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class NoInactiveUserChecker implements UserCheckerInterface
{

    public function checkPreAuth(UserInterface $user)
    {
        if (!$user->isActive()){
            throw new CustomUserMessageAuthenticationException("Vous n'étes pas autorisé à vous connecter (Banni ou compte annulé)");
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
        if (!$user->isActive()){
            throw new CustomUserMessageAuthenticationException("Vous n'étes pas autorisé à vous connecter (Banni ou compte annulé)");
        }
    }
}