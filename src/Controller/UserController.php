<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $user->setEmail('joseph@joseph.io');
        $userForm = $this->createForm(UserType::class, $user);

        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            /** @var User $userData */
            $user = $userForm->getData();
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);

            $entityManager->persist($user);
            $entityManager->flush();
            dump($user);

            return $this->redirectToRoute('homepage');
        }

        return $this->render('user/register.html.twig', [
            'user_form' => $userForm->createView()
        ]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(): Response
    {
        return $this->render('user/signin.html.twig');
    }
}
