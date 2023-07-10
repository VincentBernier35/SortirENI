<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/', name: 'admin_')]
class AdminGestionSitesController extends AbstractController
{
    #[Route('/admin/gestion/sites', name: 'gestion_sites')]
    public function index(Request $request, ): Response
    {
        return $this->render('admin_gestion_sites/index.html.twig', [
            'controller_name' => 'AdminGestionSitesController',
        ]);
    }
}
