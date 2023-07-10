<?php

namespace App\Controller;

use App\Entity\City;
use App\Form\AddCitiesFormType;
use App\Repository\CityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddCityController extends AbstractController
{
    #[Route('/admin/city/add', name: 'admin_add_city')]
    public function index(Request $request, CityRepository $cityRepository): Response
    {
        $newCity = new City();
        $addCitiesForm = $this->createForm(AddCitiesFormType::class, $newCity);
        $addCitiesForm->handleRequest($request);

        if($addCitiesForm->isSubmitted()){
            $cityRepository->save($newCity, true);
            // message flash
            $this->addFlash('success', 'la ville à bien été ajouté !');
        }

        return $this->render('city/addCity.html.twig', [
            'addCitiesForm' => $addCitiesForm,

        ]);
    }
}
