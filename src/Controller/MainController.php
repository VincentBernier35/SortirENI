<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'main_home', methods: ['GET'])]
    public function home(Security $security):Response{
        if ($security->getUser()) {
            return $this->redirectToRoute('app_accueil');
        } else {
            return $this->render('accueil/welcome.html.twig');
        }
    }

    #[Route('/test', name: 'main_home_test', methods: ['GET'])]
    public function test():Response{
        return $this->render("main/test.html.twig");
    }
}