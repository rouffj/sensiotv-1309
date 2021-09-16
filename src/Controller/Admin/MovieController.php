<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    /**
     * @Route("/admin/movie/list", name="admin_movie_index")
     */
    public function index(): Response
    {
        return new Response('You are on /admin/movie page requiring ROLE_ADMIN role');
    }

    /**
     * @Route("/admin/movie/edit/{id}", name="admin_edit")
     */
    public function edit(): Response
    {
        return new Response('You are on /admin/movie/edit/{id} page requiring ROLE_ADMIN role');
    }
}