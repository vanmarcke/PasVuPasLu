<?php

namespace App\Controller;

use App\Entity\Livres;
use App\Entity\Comment;
use App\Services\random;
use App\Form\LivreFormType;
use App\Form\CommentFormType;
use App\Repository\UserRepository;
use App\Repository\LivresRepository;
use App\Repository\BandeauRepository;
use App\Repository\CommentRepository;
use App\Repository\ContentRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;



class LivreController extends AbstractController
{
    use HelperTrait;


    /**
     * @Security("is_granted('ROLE_AUTEUR', 'ROLE_ADMIN')")
     * @Route("/ajouter_livre", name="ajouter_livre")
     * @param Request $request
     * @param UserRepository $userRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function AjouterLivre(Request $request, UserRepository $userRepository)
    {

        $livres = new Livres();

        $user = $userRepository->find($this->getUser()->getId());

        $form = $this->createForm(LivreFormType::class, $livres)
            ->handleRequest($request);

        if ($form->isSubmitted()&& $form->isValid()){
            
            // if(empty($form["photo"]->getData())) {
            //     die('Vous devez télécharger une photo de couverture pour le livre !');
            // }

            
            if($form["photo"]->getData()) {
                $photo = $livres->getPhoto();
                $fileName = $this->slugify($livres->getTitre()).'.'.$photo->guessExtension();
                
                try {
                    $photo->move(
                        $this->getParameter('livre_assets_dir'),
                        $fileName
                    );
                } catch (FileException $e) {
    
                }
                $livres->setPhoto($fileName);
            } else {
                $livres->setPhoto('default.jpg');
            }
            $em=$this->getDoctrine()->getManager();
            $livres->setUser($this->getUser());
            $em->persist($livres);
            $em->flush();

            $entityManager = $this->getDoctrine()->getManager();
            $user->setIsLivre(true);
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success',
                'Félicitations votre livre a été ajouté avec succès !');

            return $this->redirectToRoute('show_livre', [
                'slug' => $livres->getSlug(),
                'id' => $livres->getId()
            ]);
        }

//    Affichage dans la vue
        return $this->render('livre/ajouter_livres.html.twig', [
            'form'=>$form->createView(),
            'islivre' => $user->getisLivre(),
        ]);


    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("edit_livre/{id}", name="edit_livres")
     * @param Livres $livres
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editLivre($id, Livres $livres, Request $request, random $random, LivresRepository $livresRepository) {

        if($livres->getUser()->getId() != $this->getUser()->getId()) {
            return $this->redirectToRoute("show_livres");
        }

        $editLivre = $this->createForm("App\Form\LivreFormType", $livres);

        $editLivre->handleRequest($request);
        
        $img = $livresRepository->find($livres)->getPhoto();

        if($editLivre->isSubmitted() && $editLivre->isValid()) {

            if($livres->getPhoto()!=null){
                $photo = $livres->getPhoto();
                $fileName = $this->slugify($random->random()).'.'.$photo->guessExtension();
    
                // Move the file to the directory where brochures are stored
                try {
                    $photo->move(
                        $this->getParameter('livre_assets_dir'),
                        $fileName
                    );
                } catch (FileException $e) {
    
                }
                //Mise à jour de l'image
                $livres->setPhoto($fileName);
            } else {
                //Mise à jour de l'image
                $livres->setPhoto($request->get('photo-hidden'));
            }


            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success',
                'Les modifications ont bien été enregistrées');

            return $this->redirectToRoute("show_livre", [
                'slug' => $livres->getSlug(),
                'id' => $id
            ]);
        }

        return $this->render("livre/edit_livres.html.twig", [
            'form' => $editLivre->createView(),
            'livre' => $img,
            'books' => $livres
        ]);

    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("delete_livre/{id}", name="delet_livre", methods={"GET", "DELETE"})
     * @param $id
     * @param Livres $livres
     * @param LivresRepository $livresRepository
     * @param CommentRepository $commentRepository
     * @param UserRepository $userRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete($id, Livres $livres, LivresRepository $livresRepository, CommentRepository $commentRepository, UserRepository $userRepository) {

        if($livres->getUser() != $this->getUser()) {
            return $this->redirectToRoute("show_livres");
        } else {

            $delete = $livresRepository->find($id);
            $comments = $commentRepository->findOneBy(['livre' => $id]);

            if (!$delete) {
                throw $this->createNotFoundException('No book found');
            }

            $em = $this->getDoctrine()->getManager();
            if($comments) { $em->remove($comments); }
            $em->remove($delete);
            $em->flush();

            $user = $userRepository->find($this->getUser()->getId());
            $entityManager = $this->getDoctrine()->getManager();
            $user->setIsLivre(null);
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', "Le livre a bien été supprimé");
            return $this->redirectToRoute("register_user_profil");
        }

    }


    /**
     * @Route("/livre/{slug}/{id}", name="show_livre", methods={"GET", "POST"})
     * @param Request $request
     * @param Livres $livres
     * @param Comment|null $comment
     * @param SessionInterface $session
     * @param CommentRepository $commentRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */

    public function showLivre($id, Request $request, Livres $livres, Comment $comment = null, SessionInterface $session, CommentRepository $commentRepository)
    {
        $comment = new Comment();

        $form = $this->createForm(CommentFormType::class, $comment)
            ->handleRequest($request);

        if ($form->isSubmitted()&& $form->isValid()){

            $em=$this->getDoctrine()->getManager();
            $comment->setUser($this->getUser());
            $comment->setLivre($livres);
            $em->persist($comment);
            $em->flush();

            $this->addFlash('success',
                'Votre commentaire a bien été ajouté');

            return $this->redirectToRoute('show_livre', [
                'slug' => $livres->getSlug(),
                'id' => $id
            ]);
        }

        $isComment = $commentRepository->findBy(['livre' => $livres->getId()]);
        $isComment = $commentRepository->findBy(['livre' => $livres->getId()]);

        $rateTotal = $isComment ? $commentRepository->findAllGreaterThanRate($livres->getId()) : null;
        $coverRateTotal = $isComment ? $commentRepository->findAllGreaterThanCoverRate($livres->getId()) : null;
        
        return $this->render('livre/livre.html.twig', [
            'livre'=>$livres,
            'form'=>$form->createView(),
            'comment'=> $comment,
            'isComment' => $isComment,
            'rateTotal' => round(number_format($rateTotal, 2)),
            'coverRateTotal' => round(number_format($coverRateTotal, 2)),
            
        ]);
    }

    /**
     * @Route("/livres", name="show_livres")
     * @param CommentRepository $commentRepository
     * @param BandeauRepository $BandeauR
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */

    public function showLivres(CommentRepository $commentRepository, LivresRepository $livresRepository, BandeauRepository $BandeauR, ContentRepository $contentRepository)
    {
        $bandeau = $BandeauR->findByCle('page_livre');
        $carroussel = $contentRepository->findByCle('bandeau_livre');
        $carroussel_first = $contentRepository->findByCle('bandeau_livre_first');

        
        $repository = $this->getDoctrine()
            ->getRepository(Livres::class);
        $livres = $repository->ListBooks('10');
        
        $lastRate = $commentRepository->lastRate();

        $lastLivre = [];
        foreach ($lastRate as $test) {
            $lastLivre[] = $livresRepository->findBy(['id' => $test['id']]);
        }

        return $this->render('pages/books.html.twig', [
            'livres' => $livres,
            'noteLivre' => $lastLivre,
            'bandeau' => $bandeau[0],
            'carroussel' => $carroussel, 
            'carroussel_first' => $carroussel_first[0]

        ]);
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("rapport/{id}", name="raport_comment", methods={"POST"})
     * @param Comment $comment
     * @param Request $request
     * @param ObjectManager $em
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function rapport(Comment $comment, Request $request, ObjectManager $em) {

        if($this->isCsrfTokenValid('rapport' . $comment->getId(), $request->get("_token"))) {

            $comment->setRapport('1');
            $em->persist($comment);
            $em->flush();
            $this->addFlash("success", "Merci, votre signalement a bien été pris en compte. Nous vous remercions pour l'intérêt que vous portez au site pasvupaslu.com");

        }

        return $this->redirectToRoute("show_livre", [
            'slug' => $comment->getLivre()->getSlug(),
            'id' => $comment->getLivre()->getId()
        ]);

    }
    
    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("edit/comment/{id}", name="edit_comment", methods={"POST"})
     * @param Comment $comment
     * @param Request $request
     * @param ObjectManager $em
     * @return Response
     */
    public function edit_comment(Comment $comment, Request $request, ObjectManager $em) {


//        if($this->isCsrfTokenValid('edit' . $comment->getId(), $request->get("_token"))) {

            if($comment->getUser() === $this->getUser()) {

                $form = $this->createForm(CommentFormType::class, $comment)
                    ->handleRequest($request);


                if ($form->isSubmitted()&& $form->isValid()){
                    $em->persist($comment);
                    $em->flush();
                    return new Response(json_encode(['status'=>'success', 'message' => 'Le commentaire a bien été envoyé.']));
                }

                return $this->render('livre/edit-comment.html.twig', [
                    'form_edit'=>$form->createView(),
                    'id' => $comment->getId()
                ]);

            } else {

                return new Response(json_encode(['status'=>'errors', 'message' => "vous n'avez pas les autorisations pour effectuer cette modification"]));


            }

//        }
//
//        return new Response(json_encode(['status'=>'errors', 'message' => "Le token de sécurité n'est pas valide"]));

    }


}
