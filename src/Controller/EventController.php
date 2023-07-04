<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    #[Route(path:'/createEvent', name: 'createEvent', methods: ['GET', 'POST'])]
    public function createEvent(Request $request, EntityManagerInterface $em,)
    {
        $event = new Event();
        $eventform = $this->createForm(EventFormType::class, $event);
        $eventform ->handleRequest($request);
        if($eventform->isSubmitted() && $eventform->isValid()){
            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute('{{id}}', ['id'=>$event->getId()]);
        }
        return $this->render('event/event.html.twig', [
            'registrationForm' => $eventform->createView(),
        ]);
    }

    #[Route('{id}', name: 'event', requirements:['id'=>'\d+'], methods:['GET'])]
    public function show(int $id, UserRepository $userRepository):Response{
        $event = $userRepository->find($id);
        if(!$event){
            throw $this->createNotFoundException('Event inconnu');
        }
        $this->addFlash('notice', 'Votre sortie a bien créeé !');
        return $this->render('event/showOneEvent.html.twig', ['event' => $event]);
    }
}