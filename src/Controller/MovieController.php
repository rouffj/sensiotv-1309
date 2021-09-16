<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\Review;
use App\Omdb\OmdbClient;
use App\Repository\MovieRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/movie", name="movie_", methods={"GET"})
 */
class MovieController extends AbstractController
{
    private $movieRepository;

    public function __construct(MovieRepository $movieRepository)
    {
        $this->movieRepository = $movieRepository;
    }

    /**
     * @Route("/{id}", name="show", requirements={"id": "\d+"})
     */
    public function show(int $id): Response
    {
        $movie = $this->movieRepository->findOneBy(['id' => $id]);

        if (!$movie) {
            throw $this->createNotFoundException();
        }

        return $this->render('movie/show.html.twig', [
            'movie' => $movie
        ]);
    }

    /**
     * @Route("/{imdbId}/import", name="import", requirements={"imdbId": "tt\d+"})
     * @IsGranted("ROLE_ADMIN", statusCode=404)
     */
    public function import(string $imdbId, OmdbClient $omdbClient, EntityManagerInterface $entityManager): Response
    {
        $result = $omdbClient->requestOneById($imdbId);
        $movie = Movie::fromApi($result);

        $entityManager->persist($movie);
        $entityManager->flush();

        return $this->redirectToRoute('movie_show', ['id' => $movie->getId()]);
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
        $movies = $this->movieRepository->findBy([], ['releaseDate' => 'DESC']);

        return $this->render('movie/latest.html.twig', [
            'movies' => $movies,
        ]);
    }

    // /movie/add_review?movie=2&user=1&rating=4
    /**
     * @Route("/add_review", name="add_review")
     */
    public function addReview(Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $movieId = $request->query->get('movie');
        $userId = $request->query->get('user');
        $rating = $request->query->get('rating');

        $movie = $this->movieRepository->findOneById($movieId);
        $user = $userRepository->findOneById($userId);

        $review = new Review();
        $review
            ->setRating($rating)
            ->setUser($user)
            ->setMovie($movie)
        ;

        $entityManager->persist($review);
        $entityManager->flush();

        dump($review);
    }
}
