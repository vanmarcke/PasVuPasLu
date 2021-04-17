<?php

namespace App\Controller;

use App\Entity\Prices;
use App\Repository\PricesRepository;
use App\Repository\BandeauRepository;
use App\Repository\ContentRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PriceController extends AbstractController
{       


    /**
     * @Route("/prix_terhoma", name="prix_terhoma", methods={"GET"})
     * @param Prices $prices
     * @param BandeauRepository $BandeauR
     */

    public function allPrices(PricesRepository $prices,BandeauRepository $BandeauR, ContentRepository $contentRepository)
    {
        $prices = $prices->findAll();
        $bandeau = $BandeauR->findByCle('page_prix');
        //dd($bandeau);
        $carroussel = $contentRepository->findByCle('bandeau_prix_terhoma');
        $carroussel_first = $contentRepository->findByCle('bandeau_prix_terhoma_first');
        return $this->render('pages/prices.html.twig', [
            'prices' => $prices,
            'bandeau'=>$bandeau[0],
            'carroussel' => $carroussel, 
            'carroussel_first' => $carroussel_first[0]
        ]);
    }
    

}
