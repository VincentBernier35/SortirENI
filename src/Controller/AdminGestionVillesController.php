<?php

namespace App\Controller;

use App\Entity\City;
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
    public function index(CityRepository $cityRepository): Response
    {
        $city = $cityRepository->findAll();

        return $this->render('admin_gestion_villes/index.html.twig', ['cities' => $city]);
    }

//    #[Route('update/{id}', name: 'gestion_villes_update')]
//    public function update(City $city, CityRepository $cityRepository): Response
//    {
//        $city =
//
//        return $this->render('admin_gestion_villes/index.html.twig', ['cities' => $city]);
//    }

//    #[Route('create', name: 'gestion_villes_create')]
//    public function create(City $city, CityRepository $cityRepository): Response
//    {
//        $city = new City();
//        $city = $cityRepository->
//
//        return $this->render('admin_gestion_villes/index.html.twig', ['cities' => $city]);
//    }

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