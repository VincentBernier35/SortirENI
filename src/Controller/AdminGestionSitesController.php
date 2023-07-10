<?php

namespace App\Controller;


use App\Entity\Site;
use App\Form\AddCampusFormType;
use App\Repository\SiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/', name: 'admin_')]
class AdminGestionSitesController extends AbstractController
{
    #[Route('campus/index', name: 'gestion_campus')]
    public function index(Request $request, SiteRepository $siteRepository): Response
    {

        //*** add form here to get update when display the list of sites - Try otherways to get a better SOC
        $newCampus = new Site();
        $addCampusForm = $this->createForm(AddCampusFormType::class, $newCampus);
        $addCampusForm->handleRequest(AddCampusFormType::class, $newCampus);

        if($addCampusForm->isSubmitted()){
            $siteRepository->save($newCampus, true);
            // message flash
            $this->addFlash('success', 'le nouveau campus à bien été ajouté !');
        }

        $campus = $siteRepository->findAll();


        return $this->render('admin_gestion_sites/campusManagement.html.twig', [
            'allCampus' => $campus,
            'addCampusForm' => $addCampusForm,
            'newCampus' => $newCampus
        ]);
    }

    #[Route('campus/{id}/supprimer', name: 'gestion_campus_delete', requirements: ['id'=>'\d+'], methods: ['GET','POST', 'DELETE'] )]
    public function delete(Site $site, Request $request, EntityManagerInterface $em, SiteRepository $siteRepository): Response
    {

        //*** add form here to get update when display the list of sites - Try otherways to get a better SOC
        $newCampus = new Site();
        $addCampusForm = $this->createForm(AddCampusFormType::class, $newCampus);
        $addCampusForm->handleRequest(AddCampusFormType::class, $newCampus);


        if($addCampusForm->isSubmitted()){
            $siteRepository->save($newCampus, true);
            // message flash
            $this->addFlash('success', 'le nouveau campus à bien été ajouté !');
        }


        if(count($site->getEvents()) > 0){
            $this->addFlash('danger','Vous ne pouvez pas supprimer ce campus. Il y a des évènements en cours !');
        } else {
            $em->remove($site);
            $em->flush();

            //Message flash
            $this->addFlash('success','La ville à bien été supprimée !');
        }

        return $this->render('admin_gestion_villes/campusManagement.html.twig', [
            'allCampus' => $siteRepository->findAll(),
            'addCampusForm' => $addCampusForm,
            'newCampus' => $newCampus
        ]);
}
}
