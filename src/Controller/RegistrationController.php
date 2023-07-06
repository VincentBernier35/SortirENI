<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'register', methods: ['GET', 'POST'])]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $em): Response
    {
        $user = new User();
        $user->setAdmin(false);
        $user->setActive(true);
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $em->persist($user);
            $em->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('profiler', ['id'=>$user->getId()]);
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('{id}/profiler', name: 'profiler', requirements:['id'=>'\d+'], methods:['GET'])]
    public function show(int $id, UserRepository $userRepository):Response{
        $user = $userRepository->find($id);
        if(!$user){
            throw $this->createNotFoundException('Utilisateur inconnu');
        }
        $this->addFlash('success', 'Bienvenue, vous vous êtes bien inscrit !');
        return $this->render('registration/profiler.html.twig', ['user' => $user]);
    }

    #[Route(path:'{id}/editProfil', name: 'editProfil',requirements:['id'=>'\d+'], methods: ['GET', 'POST'])]
    public function editProfil(int $id, Request $request, UserRepository $userRepository, EntityManagerInterface $em):Response
    {
        $user=$userRepository->find($id);
        if(!$user){
            throw $this->createNotFoundException('L\'utilisateur n\'exist pas !');
        }

        $registrationForm=$this->createForm(RegistrationFormType::class, $user);
        $registrationForm->handleRequest($request);

        if($registrationForm->isSubmitted()&&$registrationForm->isValid()){
            if ($request->request->has('editProfil')){
                $em->flush();
                $this->addFlash('success', 'Votre profil mise à jour avec succès ! ');
                return $this->redirectToRoute('profiler', ['id' => $user->getId()]);
            }elseif ($request->request->has('deleteProfil')){
                $em->remove($user);
                $em->flush();
                $this->addFlash('success','Au revoir !');
                return $this->redirectToRoute('main_home');
            }else{
                return $this->redirectToRoute('profiler', ['id' => $user->getId()]);
            }
        }
        return $this->render('registration/editProfil.html.twig', ['registrationForm' => $registrationForm, 'user'=>$user]);
    }
}
