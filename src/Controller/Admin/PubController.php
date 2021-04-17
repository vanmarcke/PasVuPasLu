<?php

namespace App\Controller\Admin;

use App\Services\random;
use App\Form\ContentType;
use App\Form\ContentAreaType;
use App\Form\ContentphotoType;
use App\Controller\HelperTrait;
use App\Repository\ContentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface as EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PubController extends AbstractController
{
    use HelperTrait;
    /**
     * @Route("/Admin/pub_edit{cle}", name="pub1")
     */
    public function pub($cle, ContentRepository $contentRepository, Request $request, EntityManager $em)
    {
        $pub1 = $contentRepository->findByCle($cle);
        
        $form_pub1= $this->createForm(ContentAreaType::class, $pub1[0])
        ->handleRequest($request);
        
        if($form_pub1->isSubmitted() && $form_pub1->isValid()){
            // dd($pub1);
            
            $em=$this->getDoctrine()->getManager();
            $em->flush();
    
            $this->addFlash('success','Votre contenu a bien été mis à jour.');
    
            return $this->redirectToRoute('admin-pub');

        }
        return $this->render('admin/pages/formpuce.html.twig', [
                'formpuce' => $form_pub1->createView(),
               
            ]); 
    }
    /**
     * @Route("/Admin/pub", name="admin-pub")
     */
    public function adminpub(ContentRepository $contentRepository, Request $request, EntityManager $em)
    {
        
        return $this->render('admin/pages/pub.html.twig', [
            'pub1' => $contentRepository->findByCle('pub1'),
            'pub2' => $contentRepository->findByCle('pub2'),
            'pub1_img' => $contentRepository->findByCle('pub1_img'),
            'pub2_img' => $contentRepository->findByCle('pub2_img'),
            'pub1_video' => $contentRepository->findByCle('pub1_video'),
            'pub2_video' => $contentRepository->findByCle('pub2_video'),

        ]);
        
    }

    /**
     * @Route("/Admin/pub_img_edit{cle}", name="pub_img_edit")
     */
    public function pub_img_edit($cle, ContentRepository $contentRepository, Request $request, EntityManager $em,  Random $random)
    {
        $pub1 = $contentRepository->findByCle($cle);
        // dd($pub1);

        $form_pub1= $this->createForm(ContentphotoType::class, $pub1[0])
        ->handleRequest($request);
        
        if($form_pub1->isSubmitted() && $form_pub1->isValid()){
            $image = $form_pub1['file']->getData();
            if($image){
                $fileName = $this->slugify($random->random(10)).'.'.$image->guessExtension();
    
                $image->move($this->getParameter('illustration_assets_dir'), $fileName);
            }
            $pub1[0]->setContent($fileName);
            $em=$this->getDoctrine()->getManager();
            $em->flush();
    
            $this->addFlash('success','Votre contenu a bien été mis à jour.');
    
            return $this->redirectToRoute('admin-pub');

        }
        return $this->render('admin/pages/carroussel_photo.html.twig', [
                'formCarroussel' => $form_pub1->createView(),
                'taille' => 'Dimension : 960 x 940',
                
            ]); 
    }

    /**
     * @Route("/Admin/pub_video_edit{cle}", name="pub_video")
     */
    public function pub_video_edit($cle, ContentRepository $contentRepository, Request $request, EntityManager $em,  Random $random)
    {
        $pub1 = $contentRepository->findByCle($cle);
        // dd($pub1);

        $form_pub1= $this->createForm(ContentType::class, $pub1[0])
        ->handleRequest($request);
        
        if($form_pub1->isSubmitted() && $form_pub1->isValid()){

            $em=$this->getDoctrine()->getManager();
            $em->flush();
    
            $this->addFlash('success','Votre contenu a bien été mis à jour.');
    
            return $this->redirectToRoute('admin-pub');

        }
        return $this->render('admin/pages/formpuce.html.twig', [
                'formpuce' => $form_pub1->createView(),
               
            ]); 
    }
    /**
     * @Route("/Admin/pub_video_rem{cle}", name="pub_video_rem")
     */
    public function pub_video_rem($cle, ContentRepository $contentRepository, EntityManager $em)
    {
        $pub1 = $contentRepository->findByCle($cle);
       
        $pub1[0]->setContent(NULL);
        $em=$this->getDoctrine()->getManager();
        $em->flush();
    
        $this->addFlash('success','Le lien vidéo a bien été supprimé.');
            
        return $this->redirectToRoute('admin-pub');

    }

    /**
     * @Route("/Admin/pub_img_rem{cle}", name="pub_img_rem")
     */
    public function pub_img_rem($cle, ContentRepository $contentRepository, EntityManager $em)
    {
        $pub1 = $contentRepository->findByCle($cle);
       
        $pub1[0]->setContent(NULL);
        $em=$this->getDoctrine()->getManager();
        $em->flush();
    
        $this->addFlash('success','Le lien de l\'image a bien été supprimé.');
            
        return $this->redirectToRoute('admin-pub');

    }
}
