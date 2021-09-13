<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    /**
     * @Route("/movie/{id}", name="movie_show", methods={"GET"}, requirements={"id": "\d+"})
     */
    public function show(int $id): Response
    {
        dump($id);

        return $this->render('movie/show.html.twig', [
            'controller_name' => 'MovieController',
        ]);
    }
}
