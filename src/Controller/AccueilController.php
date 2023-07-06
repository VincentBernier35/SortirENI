<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\AccueilFormType;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'app_accueil', methods: ['POST','GET'])]
    public function accueil(Request $request,EventRepository $eventRepository, Security $security): Response
    {
        if ($security->getUser()) {

            $event = new Event();
            $eventForm = $this->createForm(AccueilFormType::class, $event);
            $eventForm->handleRequest($request);

            if ( $eventForm->isSubmitted() && $eventForm->isValid() ) {
                $siteID = $eventForm->get('site')->getData()->getId();
                $isPromoterChoice = $eventForm->get('promoter')->getData();
                $isRegisteredChoice = $eventForm->get('registered')->getData();
                $isNotRegisteredChoice = $eventForm->get('notRegistered')->getData();
                $isOldEventChoice = $eventForm->get('oldEvent')->getData();
                $keyWord = $eventForm->get('key')->getData();
                $startDateTime = $eventForm->get('startDateTime')->getData();
                $endDateTime = $eventForm->get('endDateTime')->getData();

                ($isPromoterChoice) ? ($promoterID = $this->getUser()->getId()) : ($promoterID = 0);
                ($isOldEventChoice) ? ($maximumStateValue = 6) : ($maximumStateValue = 5);
                if (is_null($keyWord)) {
                    $keyWord = '&';
                }

                $events = $eventRepository->findFilteredEvents($siteID, $startDateTime, $endDateTime, $promoterID, $keyWord, $maximumStateValue);
            } else {
                $events = $eventRepository->findAll();
                $isRegisteredChoice = null;
                $isNotRegisteredChoice = null;
            }
        } else {
            return $this->redirectToRoute('app_login');
        }

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
            ],
            'isRegisteredChoice' => $isRegisteredChoice,
            'isNotRegisteredChoice' => $isNotRegisteredChoice
        ]);
    }
}
