<?php

namespace App\Controller;

use App\Omdb\OmdbClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/movie", name="movie_", methods={"GET"})
 */
class MovieController extends AbstractController
{
    /**
     * @Route("/{id}", name="show", requirements={"id": "\d+"})
     */
    public function show(int $id): Response
    {
        return $this->render('movie/show.html.twig');
    }

    /**
     * @Route("/search", name="search")
     */
    public function search(OmdbClient $omdbClient, Request $request): Response
    {
        $keyword = $request->query->get('keyword', 'Sun');
        $result = $omdbClient->requestAllBySearch($keyword);

        return $this->render('movie/search.html.twig', [
            'movies' => $result['Search'],
            'keyword' => $keyword,
        ]);
    }

    /**
     * @Route("/latest", name="latest")
     */
    public function latest(): Response
    {
        return $this->render('movie/latest.html.twig');
    }
}
