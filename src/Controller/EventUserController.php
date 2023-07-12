<?php

namespace App\Controller;

use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path:'/user/event', name: 'event_')]
class EventUserController extends AbstractController
{
    #[Route('/event/unregistered/{id}', name: 'user_unregistered',requirements:['id'=>'\d+'], methods: ['GET', 'POST'])]
    public function unregistered(int $id, EventRepository $eventRepository, EntityManagerInterface $em): Response
    {
        try {
            $event = $eventRepository->find($id);
            $actualUser = $this->getUser();
            $event->removeUsersEvent($actualUser);
            $em->persist($event);
            $em->flush();
            $this->addFlash('success', 'Vous avez été correctement supprimé(e) de cet évènement');
        } catch (\Exception $ex) {
            $this->addFlash('danger', 'Une erreur est survenue lors de votre désinscription à cet évènement');
        }

        return $this->redirectToRoute('app_accueil');
    }

    #[Route('/event/registered/{id}', name: 'user_registered',requirements:['id'=>'\d+'], methods: ['GET', 'POST'])]
    public function registered(int $id, EventRepository $eventRepository, EntityManagerInterface $em): Response
    {
        try {
            $event = $eventRepository->find($id);
            $event->addUsersEvent($this->getUser());
            $em->persist($event);
            $em->flush();
            $this->addFlash('success', 'Vous avez été correctement ajouté(e) de cet évènement');
        } catch (\Exception $ex) {
            $this->addFlash('danger', 'Une erreur est survenue lors de votre inscription à cet évènement');
        }


        return $this->redirectToRoute('app_accueil');
    }
}
