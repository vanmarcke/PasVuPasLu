<?php

namespace App\Controller\Admin;


use App\Entity\Content;
use App\Form\QuoteType;
use App\Form\ContentType;
use App\Form\DasboardType;
use App\Form\ContentAreaType;
use App\Controller\HelperTrait;
use App\Repository\UserRepository;
use App\Repository\QuoteRepository;
use App\Repository\LivresRepository;
use App\Repository\CommentRepository;
use App\Repository\ContentRepository;
use App\Repository\DasboardRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface as EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashboardController extends AbstractController
{

    use HelperTrait;

    /**
     * @Route("admin", name="admin")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function admin() {

        return $this->redirectToRoute("admin-home");

    }

    /**
     * @Route("/admin/dashboard", name="admin-home")
     * @param DasboardRepository $dasboardRepository
     * @param QuoteRepository $quoteRepository
     * @param LivresRepository $article
     * @param CommentRepository $comment
     * @param UserRepository $user
     * @param Request $request
     * @param ObjectManager $em
     * @return Response
     */
    public function dashboard(DasboardRepository $dasboardRepository, QuoteRepository $quoteRepository, LivresRepository $article, CommentRepository $comment, UserRepository $user, Request $request, ObjectManager $em, ContentRepository $contentrepository): Response
    {

        $quote = $quoteRepository->find(1);
        
        $dasbord = $dasboardRepository->find(1);

        $lastRate = $comment->livreRate();
        $livreRate= [];
        foreach ($lastRate as $test) {
            $livreRate[] = $article->findBy(['id' => $test['id']]);
        }

        $bloc1 = $contentrepository->findByCle('Auteur_titre');
        $bloc1_start = $contentrepository->findByCle('bloc1_start');
        $bloc1_end = $contentrepository->findByCle('bloc1_end');
        $bloc2_titre = $contentrepository->findByCle('bloc2_titre');
        $bloc1_puce = $contentrepository->findByCle('Par1');
        $bloc3 = $contentrepository->findByCle('bloc3');
      
        $formAuteur_titre = $this->createForm(ContentType::class, $bloc1[0])
            ->handleRequest($request);

        if($formAuteur_titre->isSubmitted() && $formAuteur_titre->isValid()){
           
            $em=$this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success','Votre contenu a bien été mis à jour.');

            return $this->redirectToRoute('admin-home');
        }

        $formbloc3 = $this->createForm(ContentAreaType::class, $bloc3[0])
        ->handleRequest($request);

         if($formbloc3->isSubmitted() && $formbloc3->isValid()){

        $em=$this->getDoctrine()->getManager();
        $em->flush();

        $this->addFlash('success','Votre contenu a bien été mis à jour.');

        return $this->redirectToRoute('admin-home');
    }

        $formbloc2_titre = $this->createForm(ContentType::class, $bloc2_titre[0])
        ->handleRequest($request);

        if($formbloc2_titre->isSubmitted() && $formbloc2_titre->isValid()){
        $em=$this->getDoctrine()->getManager();
        $em->flush();

        $this->addFlash('success','Votre contenu a bien été mis à jour.');

        return $this->redirectToRoute('admin-home');
    }

        $formbloc1_start = $this->createForm(ContentType::class, $bloc1_start[0])
        ->handleRequest($request);

        if($formbloc1_start->isSubmitted() && $formbloc1_start->isValid()){

        $em=$this->getDoctrine()->getManager();
        $em->flush();

        $this->addFlash('success','Votre contenu a bien été mis à jour.');

        return $this->redirectToRoute('admin-home');
    }

        $formbloc1_end = $this->createForm(ContentType::class, $bloc1_end[0])
        ->handleRequest($request);

        if($formbloc1_end->isSubmitted() && $formbloc1_end->isValid()){

        $em=$this->getDoctrine()->getManager();
        $em->flush();

        $this->addFlash('success','Votre contenu a bien été mis à jour.');

        return $this->redirectToRoute('admin-home');
    }

        $formDasboard = $this->createForm(DasboardType::class, $dasbord)
            ->handleRequest($request);

        if($formDasboard->isSubmitted() && $formDasboard->isValid()){

            $em=$this->getDoctrine()->getManager();
            $em->persist($dasbord);
            $em->flush();

            $this->addFlash('success','Votre contenu a bien été mis à jour.');

            return $this->redirectToRoute('admin-home');
        }

        $form = $this->createForm(QuoteType::class, $quote)
            ->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

        $em=$this->getDoctrine()->getManager();
        $em->persist($quote);
        $em->flush();

        $this->addFlash('success','La citation du jour à bien été ajouté.');

        return $this->redirectToRoute('admin-home');
    }

        return $this->render('admin/pages/index.html.twig', [
            'countLivres' => $article->countLivres(),
            'countComments' => $comment->countComments(),
            'countAuteurs' => $user->countAuteurs(),
            'countLecteurs' => $user->countLecteurs(),
            'lastLivre' => $article->ListBooks(5),
            'form' => $form->createView(),
            'formDasboard' => $formDasboard->createView(), 
            'formAuteur_titre' => $formAuteur_titre->createView(),
            'formbloc1_start' => $formbloc1_start->createView(),
            'formbloc1_end' => $formbloc1_end->createView(),
            'formbloc2_titre' => $formbloc2_titre->createView(),
            'formbloc3' => $formbloc3->createView(),
            'puce' => $bloc1_puce,
            'livreRate' => $livreRate,
            
        ]);
    }

     /**
     * @Route("/admin/puce1/{id}", name="edit-puce1")
     * @return Response
     */
    public function puce($id, ContentRepository $contentrepository, Request $request, ObjectManager $em){
        
        $puce = $contentrepository->find($id);

        $formpuce = $this->createForm(ContentType::class, $puce)
            ->handleRequest($request);

        if($formpuce->isSubmitted() && $formpuce->isValid()){

            $em=$this->getDoctrine()->getManager();
            $em->persist($puce);
            $em->flush();

            $this->addFlash('success','Votre contenu a bien été mis à jour.');

            return $this->redirectToRoute('admin-home');
        }

        return $this->render('admin/pages/formpuce.html.twig', [
            'formpuce' => $formpuce->createView(),
           
        ]);  
    }
    /**
     * @Route("/admin/puce/delete{id}", name="delete-puce1")
     * @return Response
     */
    public function deletepuce($id, ContentRepository $contentrepository, Request $request, ObjectManager $em){
        $puce = $contentrepository->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($puce);
        $em->flush();

        $this->addFlash('success','Votre contenu a bien été mis à jour.');
        return $this->redirectToRoute('admin-home');
    }
    
     /**
     * @Route("/admin/puce_add", name="add-puce")
     * @return Response
     */
    public function add_puce(ContentRepository $contentrepository, Request $request, EntityManager $em){
        
        $nouveau_puce = new Content;

        $formpuce = $this->createForm(ContentType::class, $nouveau_puce)
            ->handleRequest($request);

        if($formpuce->isSubmitted() && $formpuce->isValid()){
            $nouveau_puce->setCle('Par1');
            $em->persist($nouveau_puce);
            $em->flush();

            $this->addFlash('success','Votre contenu a bien été mis à jour.');

            return $this->redirectToRoute('admin-home');
        }

        return $this->render('admin/pages/formpuce.html.twig', [
            'formpuce' => $formpuce->createView(),
           
        ]);  
    }
    /**
     * @Route("/admin/info_legal", name="info_legal")
     * @return Response
     */
    public function info_legal(ContentRepository $contentrepository, Request $request, EntityManager $em){

        $info_legal = $contentrepository->findByCle('info_legal');

        $form_info_legal = $this->createForm(ContentAreaType::class, $info_legal[0])
        ->handleRequest($request);

         if($form_info_legal->isSubmitted() && $form_info_legal->isValid()){

        $em=$this->getDoctrine()->getManager();
        $em->flush();

        $this->addFlash('success','Votre contenu a bien été mis à jour.');

        return $this->redirectToRoute('admin-home');
        }

        return $this->render('admin/pages/info_legal.html.twig', [
            'form_info_legal' => $form_info_legal->createView(),
            'titre' => 'Informations légales'
           
        ]);  
    }

    /**
     * @Route("/admin/edit/bloc/{cle}", name="bloc_edit")
     * @return Response
     */
    public function bloc_edit($cle, ContentRepository $contentrepository, Request $request, EntityManager $em){

        $Auteur_titre = $contentrepository->findByCle($cle);

        $form_info_legal = $this->createForm(ContentType::class, $Auteur_titre[0])
        ->handleRequest($request);

         if($form_info_legal->isSubmitted() && $form_info_legal->isValid()){

        $em=$this->getDoctrine()->getManager();
        $em->flush();

        $this->addFlash('success','Votre contenu a bien été mis à jour.');

        return $this->redirectToRoute('admin-home');
        }

        return $this->render('admin/pages/info_legal.html.twig', [
            'form_info_legal' => $form_info_legal->createView(),
            'titre' => 'Modifier'
           
        ]);  
    }



}
