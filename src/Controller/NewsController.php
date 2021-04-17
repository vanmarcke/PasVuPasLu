<?php

namespace App\Controller;

use App\Repository\NewsRepository;
use App\Repository\BandeauRepository;
use App\Repository\ContentRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NewsController extends AbstractController
{
    /**
     * @Route("/actualites", name="news", methods={"GET"})
     * @param News $news
     * @param BandeauRepository $BandeauR
     */

    public function allnews(newsRepository $news, BandeauRepository $BandeauR, ContentRepository $contentRepository)
    {
        $news = $news->findBy(array(), array('id' => 'desc'));
        $bandeau = $BandeauR->findByCle('page_actualite');
        $carroussel = $contentRepository->findByCle('bandeau_actualite');
        $carroussel_first = $contentRepository->findByCle('bandeau_actualite_first');

        return $this->render('pages/news.html.twig', [
            'news' => $news,
            'bandeau' => $bandeau[0],
            'carroussel' => $carroussel, 
            'carroussel_first' => $carroussel_first[0]
        ]);
    }
}
