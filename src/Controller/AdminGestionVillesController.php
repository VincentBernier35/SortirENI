<?php

namespace App\Controller;

use App\Repository\CityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/admin/', name: 'admin_')]
class AdminGestionVillesController extends AbstractController
{
    #[Route('index', name: 'gestion_villes')]
    public function index(CityRepository $cityRepository): Response
    {
        $city = $cityRepository->findAll();
//        dd($city);



        return $this->render('admin_gestion_villes/index.html.twig', ['cities' => $city]);
    }
}
