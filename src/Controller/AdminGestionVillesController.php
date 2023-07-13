<?php

namespace App\Controller;

use App\Entity\City;
use App\Form\AddCitiesFormType;
use App\Form\UpdateCityType;
use App\Repository\CityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/', name: 'admin_')]
class AdminGestionVillesController extends AbstractController
{
    #[Route('city/index', name: 'city_management_index')]
    public function index(Request$request, CityRepository $cityRepository): Response
    {
//***  Formulaire d'ajout
        $newCity = new City();
        $addCitiesForm = $this->createForm(AddCitiesFormType::class, $newCity);
        $addCitiesForm->handleRequest($request);

        if($addCitiesForm->isSubmitted()){
            $cityRepository->save($newCity, true);
            // message flash
            $this->addFlash('success', 'la ville à bien été ajouté !');
        }

        $city = $cityRepository->findAll();
        return $this->render('city/citiesManagement.html.twig', [
            'cities' => $city,
            'addCitiesForm' => $addCitiesForm,
            'newCity' => $newCity
        ]);
    }

    #[Route('city/delete/{id}', name: 'city_management_delete', requirements: ['id'=>'\d+'], methods: ['GET','POST', 'DELETE'] )]
    public function delete(City $city, Request $request, EntityManagerInterface $em, CityRepository $cityRepository): Response
    {
        $newCity = new City();

        $addCitiesForm = $this->createForm(AddCitiesFormType::class, $newCity);
        $addCitiesForm->handleRequest($request);

        if($addCitiesForm->isSubmitted()){
            $cityRepository->save($newCity, true);

            // message flash
            $this->addFlash('success', 'la ville à bien été ajouté !');

        }

        if(count($city->getPlaces()) > 0){
            $this->addFlash('danger','Vous ne pouvez pas supprimer cette ville !');
        } else {
            $em->remove($city);
            $em->flush();

            //Message flash
            $this->addFlash('success','La ville à bien été supprimée !');
        }

        return $this->render('city/citiesManagement.html.twig', [
            'cities' => $cityRepository->findAll(),
            'addCitiesForm' => $addCitiesForm,
            'newCity' => $newCity
        ]);
    }
    #[Route('city/create', name: 'city_management_create')]
    public function create(Request $request, CityRepository $cityRepository): Response
    {
        $newCity = new City();
        $addCitiesForm = $this->createForm(AddCitiesFormType::class, $newCity);
        $addCitiesForm->handleRequest($request);

        if($addCitiesForm->isSubmitted()){
            $cityRepository->save($newCity, true);
            // message flash
            $this->addFlash('success', 'la ville a bien été ajouté !');
        }

        return $this->render('city/addCity.html.twig', [
            'addCitiesForm' => $addCitiesForm,

        ]);
    }
    #[Route('city/update/{id}', name: 'city_management_update', requirements:['id'=>'\d+'], methods: ['GET','POST'])]
    public function update(City $city, Request $request, EntityManagerInterface $em): Response
    {
        $updateCityForm = $this->createForm(UpdateCityType::class, $city);
        $updateCityForm->handleRequest($request);

        if($updateCityForm->isSubmitted() && $updateCityForm->isValid()){
            $em->flush();
            $this->addFlash('success','La ville est modifiée !');
        }

        return $this->render('city/addCity.html.twig', [
            'addCitiesForm' => $updateCityForm,
            'city'=> $city
        ]);
    }
}

