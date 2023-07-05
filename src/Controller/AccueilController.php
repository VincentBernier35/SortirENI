<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\AccueilFormType;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'app_accueil', methods: ['POST','GET'])]
    public function accueil(Request $request,EventRepository $eventRepository, AccueilFormType $accueilFormType): Response
    {
        $event = new Event();
        $eventForm = $this->createForm(AccueilFormType::class, $event);
        $eventForm->handleRequest($request);

        if ($eventForm->isSubmitted() && $eventForm->isValid()) {
            $data = $eventForm->getData();
            dd($data);
            $promoter = $eventForm->get('orga')->getData();
            var_dump($promoter);
        }
        $events = $eventRepository->findAll();

        return $this->render('accueil/accueil.html.twig', [
            'events' => $events,
            'accueilForm' => $eventForm,
            'UserConnected' => $this->getUser(),
            'stateX' => [
                0 => 'En création',
                1 => 'En cours',
                2 => 'Fermé',
                3 => 'Activité en cours',
                4 => 'Activité passée',
                5 => 'Archivé'
            ]
        ]);
    }
}
