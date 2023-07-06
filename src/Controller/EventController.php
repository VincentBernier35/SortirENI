<?php

namespace App\Controller;


use App\Entity\Event;
use App\Entity\State;
use App\Form\EventFormType;
use App\Repository\EventRepository;
use App\Repository\StateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route(path:'/event', name: 'event_')]
class EventController extends AbstractController
{
    #[Route(path:'/createEvent', name: 'createEvent',requirements:['id'=>'\d+'], methods: ['GET', 'POST'])]
    public function createEvent(StateRepository $stateRepository, Request $request, EntityManagerInterface $em,Security $security):Response
    {//Toto : if no login? alter: to connecter

        $user = $this->getUser();
        $userId = $user->getId();
        $event = new Event();
        $state = $stateRepository -> findOneBy(
            ['reference'=>0]);
        $event -> setState($state);
        $event -> setPromoter($user);
        $event -> setSite($user->getSite());
        $eventForm = $this->createForm(EventFormType::class, $event);
        $eventForm -> handleRequest($request);

        if($eventForm->isSubmitted() && $eventForm->isValid()){

            $em->persist($event);
            $em->flush();
            return $this->redirectToRoute('event_event', ['id'=>$event->getId()]);
        }
        if($security->getUser()){
            return $this->render('event/event.html.twig', ['eventForm' => $eventForm, 'event' => $event, 'user'=>$user]);
        }else{
            return $this->redirectToRoute('app_login');
        }
    }

    #[Route('{id}', name: 'event', requirements:['id'=>'\d+'], methods:['GET'])]
    public function show(int $id, EventRepository $eventRepository):Response{
        $event = $eventRepository->find($id);

        if(!$event){
            throw $this->createNotFoundException('Event inconnu');
        }

        return $this->render('event/showOneEvent.html.twig', ['event' => $event]);
    }
}