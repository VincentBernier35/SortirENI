<?php

namespace App\Controller;

use App\Form\CancelEventFormType;
use App\Form\EventFormType;
use App\Repository\EventRepository;
use App\Repository\StateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path:'/event', name: 'event_')]
class StateEventController extends AbstractController
{
    #[Route('/cancel/{id}', name: 'cancelEvent',requirements:['id'=>'\d+'], methods: ['GET', 'POST'])]
    public function cancelEvent(int $id, Request $request, StateRepository $stateRepository, EventRepository $eventRepository, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $event = $eventRepository->find($id);

        $eventForm = $this->createForm(CancelEventFormType::class, $event);
        $eventForm->handleRequest($request);

        if ($request->request->has('save')) {
            try {
                $state = $stateRepository->findOneBy(['reference' => 5]);
                $event->setState($state);
                $event->setCancelReason( $eventForm->get('cancelReason')->getData() );
                $em->persist($event);
                $em->flush();
                $this->addFlash('success', 'Cet évènement est maintenant archivé/annulé');

                return $this->redirectToRoute('app_accueil');
            } catch (\Exception $ex) {
                $this->addFlash('danger', "Une erreur est survenue lors de l'archivage/annulation de cet évènement");
            }
        }
        if ($request->request->has('cancel')) {
            return $this->redirectToRoute('app_accueil');
        }

        return $this->render('event/cancelOneEvent.html.twig', ['eventForm' => $eventForm, 'event'=>$event, 'user' => $user]);
    }

    #[Route('/publish/{id}', name: 'publishEvent',requirements:['id'=>'\d+'])]
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