<?php

namespace App\Controller;


use App\Entity\Comment;
use App\Entity\Site;
use App\Form\AddCampusFormType;
use App\Form\CommentFormType;
use App\Form\UpdateCampusFormType;
use App\Repository\CommentRepository;
use App\Repository\EventRepository;
use App\Repository\SiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use http\QueryString;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    #[Route('event/comment', name: 'event_comment')]
    public function create( Request $request, CommentRepository $commentRepository): Response
    {
        $user = $this->getUser();



        $comment = new Comment();
        $commentForm = $this->createForm(CommentFormType::class, $comment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $commentRepository -> save($comment, true);
            $id_event = $request->query->get('id');
            $this->addFlash('success', 'Votre commentaire a bien été ajouté !');
            return $this->redirectToRoute('event_event', [ 'user' => $user,
                'commentForm'=>  $commentForm, 'id' => $id_event]);
        }

        return $this->render('comment/addComment.html.twig', [
            'commentForm' => $commentForm->createView(),
        ]);
    }
}
