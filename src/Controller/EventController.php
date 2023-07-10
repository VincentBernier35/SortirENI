<?php

namespace App\Controller;


use App\Entity\Event;
use App\Entity\Place;
use App\Form\EventFormType;
use App\Form\PlaceFormType;
use App\Repository\EventRepository;
use App\Repository\PlaceRepository;
use App\Repository\StateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;

#[Route(path:'/user/event', name: 'event_')]
class EventController extends AbstractController
{
    #[Route(path:'/create', name: 'createEvent',requirements:['id'=>'\d+'], methods: ['GET', 'POST'])]
    public function createEvent(StateRepository $stateRepository, Request $request, EntityManagerInterface $em,Security $security):Response
    {
        if ( $security->getUser() ){
            $user = $this->getUser();
            $event = new Event();
            $state = $stateRepository -> findOneBy(['reference'=>0]);
            $event -> setState($state);
            $event -> setPromoter($user);
            $event -> setSite($user->getSite());
            $eventForm = $this->createForm(EventFormType::class, $event);
            $eventForm -> handleRequest($request);
            if ( $eventForm->isSubmitted() && $eventForm->isValid() ){
                $em->persist($event);
                $em->flush();
                return $this->redirectToRoute('event_event', ['id'=>$event->getId()]);
            }
        } else {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('event/event.html.twig', ['eventForm' => $eventForm, 'user'=>$user]);
    }

    #[Route(path:'/place/add', name: 'add_place', methods: ['GET', 'POST'])]
    public function addPlace(Request $request, PlaceRepository $placeRepository): Response
    {
        $place = new place();
        $placeForm = $this->createForm(PlaceFormType::class, $place);
        $placeForm->handleRequest($request);

        if($placeForm->isSubmitted() && $placeForm->isValid()){
            $placeRepository->save($place, true);
            $this->addFlash('success', 'ce lieu à bien été ajouté !');
            return $this->redirectToRoute('event_createEvent');
        }
        return $this->render('event/addPlace.html.twig', ['placeForm' => $placeForm]);
    }

    #[Route(path:'/list', name: 'listEvent', methods: ['GET'])]
    public function listEvent(EventRepository $eventRepository, Security $security): Response
    {
        if($security->getUser()) {
            $user = $this->getUser();
            $events = $user -> getEvents();

            return $this->render('event/list.html.twig', [
                'events' => $events, 'user' => $user
            ]);
        }else{
            return $this->redirectToRoute('app_login');
        }
    }

    #[Route('{id}', name: 'event', requirements:['id'=>'\d+'], methods:['GET'])]
    public function show(int $id, EventRepository $eventRepository):Response{
        $user = $this->getUser();
        $event = $eventRepository->find($id);
        if(!$event){
            throw $this->createNotFoundException('Event inconnu');
        }

        return $this->render('event/showOneEvent.html.twig', ['event' => $event,'user'=>$user]);
    }
    #[Route(path:'/edit/{id}', name: 'editEvent',requirements:['id'=>'\d+'], methods: ['GET', 'POST'])]
    public function editEvent(int $id, Request $request, EventRepository $eventRepository, EntityManagerInterface $em):Response
    {
        $user = $this->getUser();
        $event=$eventRepository->find($id);
        if (!$event){
            throw $this->createNotFoundException('La sortie n\'existe pas !');
        }
        if ($event->getPromoter()!==$this->getUser()){
            throw $this->createAccessDeniedException();
        }
        $eventForm=$this->createForm(EventFormType::class, $event);
        $eventForm->handleRequest($request);

        if ($eventForm->isSubmitted()&&$eventForm->isValid()){
            if ($request->request->has('save')){
                    $em->flush();
                    $this->addFlash('success', 'La sortie mise à jour a été faites avec succès ! ');
                    return $this->redirectToRoute('event_event', ['id' => $event->getId()]);
                } elseif ($request->request->has('delete')){
                    $em->remove($event);
                    $em->flush();
                    $this->addFlash('success','La sortie a bien été supprimée !');
                    return $this->redirectToRoute('event_listEvent');
                } elseif ($request->request->has('publish')){

                    return $this->redirectToRoute('publishEvent', ['id' => $event->getId()]);
                } else {
                    return $this->redirectToRoute('app_accueil');
                }
        }
        return $this->render('event/edit.html.twig', ['eventForm' => $eventForm, 'event'=>$event, 'user' => $user]);
    }
}