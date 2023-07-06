<?php

namespace App\Controller;

use App\Repository\EventRepository;
use App\Repository\StateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StateEventController extends AbstractController
{
    #[Route('/state/cancelevent/{id}', name: 'cancelEvent')]
    public function cancelEvent(int $id, StateRepository $stateRepository, EventRepository $eventRepository, EntityManagerInterface $em): Response
    {
        try {
            $event = $eventRepository->find($id);
            $state = $stateRepository -> findOneBy(['reference'=>5]);
            $event->setState($state);
            $em->persist($event);
            $em->flush();
            $this->addFlash('success', 'Cet évènement est maintenant archivé/annulé');
        } catch (\Exception $ex) {
            $this->addFlash('danger', "Une erreur est survenue lors de l'archivage/annulation de cet évènement");
        }

        return $this->redirectToRoute('app_accueil');
    }

    #[Route('/state/publishevent/{id}', name: 'publishEvent')]
    public function publishEvent(int $id, StateRepository $stateRepository, EventRepository $eventRepository, EntityManagerInterface $em): Response
    {
        try {
            $event = $eventRepository->find($id);
            $state = $stateRepository -> findOneBy(['reference'=>1]);
            $event->setState($state);
            $em->persist($event);
            $em->flush();
            $this->addFlash('success', 'Cet évènement est maintenant publié !!');
        } catch (\Exception $ex) {
            $this->addFlash('danger', 'Une erreur est survenue lors de la publication de cet évènement');
        }

        return $this->redirectToRoute('app_accueil');
    }
}