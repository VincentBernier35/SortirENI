<?php

namespace App\Controller;


use App\Entity\Event;
use App\Form\AccueilBisFormType;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilBisController extends AbstractController
{

    #[Route('/bis', name: 'bisIndex', methods: ['POST', 'GET'])]
    public function allEvents(Request $request, EventRepository $eventRepository, Security $security): Response
    {
        if ($security->getUser()) {
            $eventBis = new Event();
            $userID = $this->getUser()->getId();
            $accueilBisForm = $this->createForm(AccueilBisFormType::class, $eventBis);
            $accueilBisForm->handleRequest($request);

            if ($accueilBisForm->isSubmitted() && $accueilBisForm->isValid()) {
                if ($accueilBisForm->get("site")->getData()) {
                    $siteID = $accueilBisForm->get('site')->getData()->getId();
                } else {
                    $siteID = 0;
                }

                $isPromoterChoice = $accueilBisForm->get('promoter')->getData();
                $isRegisteredChoice = $accueilBisForm->get('registered')->getData();
                $isNotRegisteredChoice = $accueilBisForm->get('notRegistered')->getData();
                $isOldEventChoice = $accueilBisForm->get('oldEvent')->getData();
                $keyword = $accueilBisForm->get('startDateTime')->getData();
                $startDateTime = $accueilBisForm->get('startDateTime')->getData();
                $endDateTime = $accueilBisForm->get('endDateTime')->getData();

                if ($isPromoterChoice) {
                    $promoterID = $userID;
                } else {
                    $promoterID = 0;
                }
//                ($isPromoterChoice) ? ($promoterID = $userID) : ($promoterID = 0);
                ($isOldEventChoice) ? ($maximumStateValue = 6) : ($maximumStateValue = 5);

                if (is_null($keyword)) {
                    $keyword = '--no search--';
                }

                $events = $eventRepository->findFilteredEvents($siteID, $userID, $startDateTime, $endDateTime, $promoterID, $keyword, $maximumStateValue);

            } else {
                $events = $eventRepository->findBasicEvents($userID);
                $isRegisteredChoice = null;
                $isNotRegisteredChoice = null;
            }

        } else {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('bis/index.html.twig', [
            'events' => $events,
            'accueilForm' => $accueilBisForm,
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

}