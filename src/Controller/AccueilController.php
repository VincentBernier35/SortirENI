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
    #[Route('/user/accueil', name: 'app_accueil', methods: ['POST','GET'])]
    public function accueil(Request $request,EventRepository $eventRepository, Security $security): Response
    {
        if ($security->getUser()) {

            $event = new Event();
            $userID = $this->getUser()->getId();
            $eventForm = $this->createForm(AccueilFormType::class, $event);
            $eventForm->handleRequest($request);

            if ( $eventForm->isSubmitted() && $eventForm->isValid()) {
                if ( $eventForm->get('site')->getData() ){
                    $siteID = $eventForm->get('site')->getData()->getId();
                } else {
                    $siteID = 0;
                }

                $isPromoterChoice = $eventForm->get('promoter')->getData();
                $isRegisteredChoice = $eventForm->get('registered')->getData();
                $isNotRegisteredChoice = $eventForm->get('notRegistered')->getData();
                $isOldEventChoice = $eventForm->get('oldEvent')->getData();
                $keyWord = $eventForm->get('key')->getData();
                $startDateTime = $eventForm->get('startDateTime')->getData();
                $endDateTime = $eventForm->get('endDateTime')->getData();

                ($isPromoterChoice) ? ($promoterID = $userID) : ($promoterID = 0);
                ($isOldEventChoice) ? ($maximumStateValue = 6) : ($maximumStateValue = 5);
                if (is_null($keyWord)) {
                    $keyWord = '-no search-';
                }

                $events = $eventRepository->findFilteredEvents($siteID, $userID, $startDateTime, $endDateTime, $promoterID, $keyWord, $maximumStateValue);

            } else {
                $events = $eventRepository->findBasicEvents($userID);
                $isRegisteredChoice = null;
                $isNotRegisteredChoice = null;
            }
        } else {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('accueil/accueil.html.twig', [
            'events' => $events,
            'accueilForm' => $eventForm,
            'user' => $this->getUser(),
            'stateX' => [
                0 => 'En création',
                1 => 'En cours',
                2 => 'Fermé',
                3 => 'Activité en cours',
                4 => 'Activité passée',
                5 => 'Archivé/Annulé'
            ],
            'isRegisteredChoice' => $isRegisteredChoice,
            'isNotRegisteredChoice' => $isNotRegisteredChoice
        ]);
    }

    #[Route('welcome', name: 'app_welcome', methods: ['POST','GET'])]
    public function welcome(Request $request,EventRepository $eventRepository, Security $security): Response
    {
        return $this->render('accueil/welcome.html.twig', [
            'toto' => 'toto'
        ]);
    }

}
