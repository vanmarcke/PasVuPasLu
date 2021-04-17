<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 26.03.2019
 * Time: 20:59
 */

namespace App\Controller;

use App\Entity\User;
use App\Repository\BandeauRepository;
use App\Repository\CommentRepository;
use App\Repository\ContentRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AuteurController extends AbstractController
{


    /**
     *
     * @Route("/auteurs", name="show_auters")
     * @param User $users
     * @param BandeauRepository $BandeauR
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function showAuters(BandeauRepository $BandeauR, ContentRepository $contentRepository )
    {
        $carroussel = $contentRepository->findByCle('bandeau_auteur');
        $carroussel_first = $contentRepository->findByCle('bandeau_auteur_first');
        $bandeau = $BandeauR->findByCle('page_auteur');
      
        $repository = $this->getDoctrine()
            ->getRepository(User::class);

        $auters = $repository->findLatestAuteurs('ROLE_AUTEUR', 10);
        //        dump($users);
//        die();
        return $this->render('auteur/auteurs.html.twig', [
            'users'=>$auters,
            'bandeau' => $bandeau[0],
            'carroussel' => $carroussel,
            'carroussel_first' => $carroussel_first[0]
        ]);

    }

    /**
     * @Route("/profil/{id}", name="profil_auter", methods={"GET","POST"})
     * @param $id
     * @param CommentRepository $commentRepository
     * @return Response
     */
    public function profilAuter($id, CommentRepository $commentRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $auter = $entityManager->getRepository(User::class)->find($id);

        $comments = $commentRepository->findBy(['user' => $id]);

        return $this->render('auteur/auter_profil.html.twig', [
            'user'=>$auter,
            'comments' => $comments
        ]);
    }

}
