<?php

namespace App\Controller;

use App\Entity\City;
use App\Form\AddCitiesFormType;
use App\Repository\CityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/admin/', name: 'admin_')]
class AdminGestionVillesController extends AbstractController
{
    #[Route('index', name: 'gestion_villes')]
    public function index(Request$request, CityRepository $cityRepository): Response
    {


//***  Formulaire d'ajout
        $newCity = new City();
        $addCitiesForm = $this->createForm(AddCitiesFormType::class, $newCity);
        $addCitiesForm->handleRequest($request);

        if($addCitiesForm->isSubmitted()){
            $cityRepository->save($newCity, true);

            // message flash
            $this->addFlash('succes', 'la ville à bien été ajouté !');

        }

        $city = $cityRepository->findAll();

        return $this->render('admin_gestion_villes/index.html.twig', [
            'cities' => $city,
            'addCitiesForm' => $addCitiesForm,
            'newCity' => $newCity
        ]);
    }

//    #[Route('update/{id}', name: 'gestion_villes_update')]
//    public function update(City $city, CityRepository $cityRepository): Response
//    {
//        $city =
//
//        return $this->render('admin_gestion_villes/index.html.twig', ['cities' => $city]);
//    }

    #[Route('create', name: 'gestion_villes_create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em, CityRepository $cityRepository ): Response
    {
        $cities = $cityRepository->findAll();
        $city = new City();

        $addCitiesForm = $this->createForm(AddCitiesFormType::class, $city);
        $addCitiesForm->handleRequest($request);

        if($addCitiesForm->isSubmitted()){
            $em->persist($city);
            $em->flush();

            // message flash
            $this->addFlash('success', 'la ville à bien été ajouté !');


            return $this->render('admin_gestion_villes/index.html.twig', ['cities' => $cities]);
        }

        return $this->render('admin_gestion_villes/index.html.twig', ['addCitiesForm' => $addCitiesForm]);
    }

    #[Route('{id}/supprimer', name: 'gestion_villes_delete', requirements: ['id'=>'\d+'], methods: ['GET','POST', 'DELETE'] )]
    public function delete(int $id, Request $request, EntityManagerInterface $em, CityRepository $cityRepository): Response
    {
        $city = $em->getRepository(City::class)->find($id);

        if (!$city) {
            throw $this->createNotFoundException('city not found');
        }

        if(count($city->getPlaces()) > 0){
//            $message = "Vous ne pouvez pas supprimer cette ville";
            $this->addFlash('danger','Vous ne pouvez pas supprimer cette ville !');
            return $this->render('admin_gestion_villes/index.html.twig', [
                'cities' => $cityRepository->findAll()
//                'message' => $message
            ]);
        }

        $em->remove($city);
        $em->flush();

        //Message flash
        $this->addFlash('success','La ville à bien été supprimée !');
        return $this->render('admin_gestion_villes/index.html.twig', ['cities' => $cityRepository->findAll()]);
    }
}