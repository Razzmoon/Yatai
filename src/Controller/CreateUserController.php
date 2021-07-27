<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\InscriptionType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class CreateUserController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function insertUser(Request $request,UserRepository $userRepository, EntityManagerInterface $entityManager,UserPasswordHasherInterface $userPasswordHasher)
    {
        $users = $userRepository->findAll();

        $user = new user();

        //On genere le formulaire en utilisant le gabarit + une instance de l'entité article
        $userForm = $this->createForm(InscriptionType::class, $user);

        //on lie le formulaire au donné de POST (donné envoyer par post)
        $userForm->handleRequest($request);

        //si le form a été poster et qu'il et valide (que tous les champ obligatoire son bien rempli)
        //alors on enregistre l'article en bdd=
        if ($userForm->isSubmitted()&&$userForm->isValid()){
            $plainPassword = $userForm->get('password')->getData();
            $hashedPassword = $userPasswordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);

            // permet de stocker en session un message flash, dans le but de l'afficher
            // sur la page suivante
            $this->addFlash(
                'success',
                'Le compte '. $user->getName().' a bien été créé !'
            );

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_login');

        }

        return $this->render('security/register.html.twig', [
            'userForm' => $userForm->createView(),
            'users' => $users

        ]);
    }
}