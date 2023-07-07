<?php

namespace App\Controller;

use App\Entity\City;
use App\Form\AddCitiesFormType;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UpdateCityController extends AbstractController
{
    #[Route('/{id}/updateCity', name: 'app_update_city', requirements:['id'=>'\d+'], methods: ['GET','POST'])]
    public function index(int $id, Request $request, EntityManagerInterface $em): Response
    {
        $newCity = new City();
        $updateCityForm = $this->createForm(AddCitiesFormType::class, $newCity);
        $updateCityForm->handleRequest($request);



        return $this->render('update_city/index.html.twig', [
            'controller_name' => 'UpdateCityController',
            'updateCityForm' => $updateCityForm
        ]);
    }
}
