<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('admin/register', name: 'register', methods: ['GET', 'POST'])]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $em, FileUploader $fileUploader): Response
    {
        $user = new User();
        $user->setAdmin(false);
        $user->setActive(true);
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form->get('image')->getData();
            if ($imageFile){
                $user->setImage($fileUploader->upload($imageFile));
            }

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

    #[Route('profil/{id}', name: 'profiler', requirements:['id'=>'\d+'],methods:['GET'])]
    public function show(int $id, UserRepository $userRepository):Response{
        $user = $userRepository->find($id);
        if(!$user){
            throw $this->createNotFoundException('Utilisateur inconnu');
        }

        return $this->render('registration/profiler.html.twig', ['user' => $user]);
    }

    #[Route(path:'admin/profil/edit/{id}', name: 'editProfil',requirements:['id'=>'\d+'], methods: ['GET', 'POST'])]
    public function editProfil(int $id, Request $request, UserRepository $userRepository, EntityManagerInterface $em, FileUploader $fileUploader):Response
    {
        $user = $userRepository->find($id);
        if (!$user){
            throw $this->createNotFoundException('L\'utilisateur n\'existe pas !');
        }

        $registrationForm=$this->createForm(RegistrationFormType::class, $user);
        $registrationForm->handleRequest($request);

        if ($registrationForm->isSubmitted() && $registrationForm->isValid()){
            if ($request->request->has('saveProfil')){
                // On supprime l'image ou on insere une nouvelle
                $imageFile = $registrationForm->get('image')->getData();
                if (($registrationForm->has('deleteImage') && $registrationForm['deleteImage']->getData()) || $imageFile) {

                    $fileUploader->delete($user->getImage(), $this->getParameter('app.images_user_directory'));

                    if ($imageFile) {
                        $user->setImage($fileUploader->upload($imageFile));
                    } else {
                        $user->setImage(null);
                    }
                }

                $em->flush();
                $this->addFlash('success', 'Votre profil a été mise à jour avec succès ! ');
                return $this->redirectToRoute('profiler', ['id' => $user->getId()]);
            }
            elseif ($request->request->has('deleteProfil')){
                $em->remove($user);
                $em->flush();
                return $this->redirectToRoute('/logout');
            }
            else{
                return $this->redirectToRoute('profiler', ['id' => $user->getId()]);
            }
        }
        return $this->render('registration/editProfil.html.twig', ['registrationForm' => $registrationForm, 'user'=>$user]);
    }
}
