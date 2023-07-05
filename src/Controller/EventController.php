<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Event;
use App\Entity\Place;
use App\Entity\User;
use App\Form\EventFormType;
use App\Form\PlaceFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    #[Route(path:'/createEvent', name: 'createEvent',requirements:['id'=>'\d+'], methods: ['GET', 'POST'])]
    public function createEvent(Request $request, EntityManagerInterface $em,):Response
    {
        //Toto : if no login? alter: to connecter

        $user = $this->getUser();
        $city = new City();
        $event = new Event();
        $eventForm = $this->createForm(EventFormType::class, $event);
        $eventForm -> handleRequest($request);

        $place = new Place();
        $placeForm = $this->createForm(PlaceFormType::class, $place);
        $placeForm -> handleRequest($request);
        if($eventForm->isSubmitted() && $eventForm->isValid() && $placeForm->isSubmitted() && $placeForm->isValid()){
            $em->persist($event);
            $em->persist($place);
            $em->flush();
            return $this->redirectToRoute('{{id}}', ['id'=>$event->getId()]);
        }
        return $this->render('event/event.html.twig', ['eventForm' => $eventForm,
                                                            'placeForm' => $placeForm,
                                                            'event' => $event, 'user'=>$user,
                                                            'place'=>$place, 'city'=>$city]);
    }

    #[Route('/events', name: 'event', requirements:['id'=>'\d+'], methods:['GET'])]
    public function show(int $id, UserRepository $userRepository):Response{
        $event = $userRepository->find($id);
        if(!$event){
            throw $this->createNotFoundException('Event inconnu');
        }
        $this->addFlash('notice', 'Votre sortie a bien créeé !');
        return $this->render('event/showOneEvent.html.twig', ['event' => $event]);
    }
}