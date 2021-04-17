<?php

namespace App\Controller;

use App\Repository\BandeauRepository;
use App\Repository\ContentRepository;
use App\Repository\PodcastRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PodcastController extends AbstractController
{
    /**
     * @Route("/podcast", name="podcast")
     * @param BandeauRepository $BandeauR
     */
    public function index(PodcastRepository $podcastrepository,BandeauRepository $BandeauR, ContentRepository $contentRepository)
    {
        $bandeau = $BandeauR->findByCle('page_podcast');
        $carroussel = $contentRepository->findByCle('bandeau_podcast');
        $carroussel_first = $contentRepository->findByCle('bandeau_podcast_first');

        return $this->render('pages/podcast.html.twig', [
            'podcast'=> $podcastrepository->findBy([],[],10),
            'bandeau' => $bandeau[0],
            'carroussel' => $carroussel,
            'carroussel_first' => $carroussel_first[0]
        ]);
    }
}
