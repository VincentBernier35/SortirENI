<?php

namespace App\Controller;


use App\Entity\Event;
use App\Form\AccueilBisFormType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilBisController extends AbstractController
{
//    #[Route('/bis/{$id}', name: 'test_accueil_bis')]
//    public function accueilBis(Event $event, $id, EntityManager $entityManager, Request $request, EventRepository $eventRepository): Response
//    {
//        $oneEvent = $event->getEvents();
//
//        return $this->render('bis/index.html.twig', [
//            'controller_name' => 'AccueilBisController',
//            'event' => $event,
//            'oneEvent' => $oneEvent
//        ]);
//    }
    #[Route('/bis', name: 'bisIndex')]
    public function allEvents(EventRepository $eventRepository): Response{


        return $this->render('bis/index.html.twig', [

            'events' => $eventRepository->findAll()

        ]);
    }

}
