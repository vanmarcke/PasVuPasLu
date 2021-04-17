<?php

namespace App\Controller\Admin;

use App\Entity\Content;
use App\Services\random;
use App\Form\ContentphotoType;
use App\Controller\HelperTrait;
use App\Form\ContentBandeauType;
use App\Repository\ContentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface as EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CarrousselController extends AbstractController
{    
    use HelperTrait;
    /**
     * @Route("/admin/carroussel", name="carroussel")
     * @return Response
     */
    public function carroussel(ContentRepository $contentrepository, Request $request, EntityManager $em){

        $carroussel_1 = $contentrepository->findByCle('carroussel_1');
        $carroussel = $contentrepository->findByCle('carroussel');
        return $this->render('admin/pages/carroussel.html.twig', [
            'carroussel_1'=> $carroussel_1[0],
            'carroussel'=> $carroussel
           
        ]);  
    }
    /**
     * @Route("/admin/carroussel/img_edit{cle}", name="carroussel_photo")
     * @return Response
     */
    public function carroussel_photo($cle, ContentRepository $contentrepository, Request $request, EntityManager $em, Random $random){

        $nouvelle_image = $contentrepository->findByCle($cle); 

        $formCarroussel = $this->createForm(ContentBandeauType::class, $nouvelle_image[0])
            ->handleRequest($request);

        if($formCarroussel->isSubmitted() && $formCarroussel->isValid()){

        $image = $formCarroussel['file']->getData();
        if($image){
            $fileName = $this->slugify($random->random(10)).'.'.$image->guessExtension();

            $image->move($this->getParameter('illustration_assets_dir'), $fileName);
            $nouvelle_image[0]->setContent($fileName);
        }
        $em=$this->getDoctrine()->getManager();
        $em->flush();

        $this->addFlash('success','Votre contenu a bien été mis à jour.');
        return $this->redirectToRoute('carroussel');
        }

        return $this->render('admin/pages/carroussel_photo_bandeau.html.twig', [
            'formCarroussel' => $formCarroussel->createView(),
            'titre' => 'Carroussel'
           
        ]);  
    }

    /**
     * @Route("/admin/carroussel/img/{id}", name="carroussel_photo_id")
     * @return Response
     */
    public function carroussel_photo_id($id, ContentRepository $contentrepository, Request $request, EntityManager $em, Random $random){

        $nouvelle_image = $contentrepository->find($id); 
        $formCarroussel = $this->createForm(ContentBandeauType::class, $nouvelle_image)
            ->handleRequest($request);

        if($formCarroussel->isSubmitted() && $formCarroussel->isValid()){

        $image = $formCarroussel['file']->getData();
        if($image){
            $fileName = $this->slugify($random->random(10)).'.'.$image->guessExtension();

            $image->move($this->getParameter('illustration_assets_dir'), $fileName);
        }
        $nouvelle_image->setContent($fileName);
        $em=$this->getDoctrine()->getManager();
        $em->flush();

        $this->addFlash('success','Votre contenu a bien été mis à jour.');
        return $this->redirectToRoute('carroussel');
        }

        return $this->render('admin/pages/carroussel_photo_bandeau.html.twig', [
            'formCarroussel' => $formCarroussel->createView(),
            'titre' => 'Modifier l\'image'
           
        ]);  
        }

    /**
     * @Route("/admin/carroussel/img_add", name="carroussel_add")
     * @return Response
     */
    public function carroussel_add(ContentRepository $contentrepository, Request $request, EntityManager $em, Random $random){

        $nouvelle_image = new Content;
        $formCarroussel = $this->createForm(ContentphotoType::class, $nouvelle_image)
            ->handleRequest($request);

        if($formCarroussel->isSubmitted() && $formCarroussel->isValid()){

        $image = $formCarroussel['file']->getData();
        if($image){
            $fileName = $this->slugify($random->random(10)).'.'.$image->guessExtension();

            $image->move($this->getParameter('illustration_assets_dir'), $fileName);
        }
        $nouvelle_image->setCle('carroussel');
        $nouvelle_image->setContent($fileName);
        $em=$this->getDoctrine()->getManager();
        $em->persist($nouvelle_image);
        $em->flush();

        $this->addFlash('success','Votre contenu a bien été ajouté.');
        return $this->redirectToRoute('carroussel');
        }

        return $this->render('admin/pages/carroussel_photo.html.twig', [
            'formCarroussel' => $formCarroussel->createView(),
            'titre' => 'Ajouter image'
           
        ]);  
        }

    /**
     * @Route("/admin/carroussel/img_rem/{id}", name="carroussel_rem")
     * @return Response
     */
    public function carroussel_rem($id, ContentRepository $contentrepository, Request $request, EntityManager $em, Random $random){

        $nouvelle_image = $contentrepository->find($id);

        $em=$this->getDoctrine()->getManager();
        $em->remove($nouvelle_image);
        $em->flush();

        $this->addFlash('success','Votre contenu a bien été supprimé.');
        return $this->redirectToRoute('carroussel');
        }

    //*************************CARROUSSEL BAS****************************** */    
    
    /**
     * @Route("/admin/carroussel_bas", name="carroussel_bas")
     * @return Response
     */
    public function carroussel_bas(ContentRepository $contentrepository, Request $request, EntityManager $em){

        return $this->render('admin/pages/carroussel_bas.html.twig', [
            'carroussel'=> $contentrepository->findByCle('carroussel_bas')
           
        ]);  
    }

    /**
     * @Route("/admin/carroussel_bas/img_edit{id}", name="carroussel_photo_bas")
     * @return Response
     */
    public function carroussel_photo_bas($id, ContentRepository $contentrepository, Request $request, EntityManager $em, Random $random){

        $nouvelle_image = $contentrepository->find($id); 

        $formCarroussel = $this->createForm(ContentBandeauType::class, $nouvelle_image)
            ->handleRequest($request);

        if($formCarroussel->isSubmitted() && $formCarroussel->isValid()){

        $image = $formCarroussel['file']->getData();
        if($image){
            $fileName = $this->slugify($random->random(10)).'.'.$image->guessExtension();

            $image->move($this->getParameter('illustration_assets_dir'), $fileName);
            $nouvelle_image->setContent($fileName);
        }
        $em=$this->getDoctrine()->getManager();
        $em->flush();

        $this->addFlash('success','Votre contenu a bien été mis à jour.');
        return $this->redirectToRoute('carroussel_bas');
        }

        return $this->render('admin/pages/carroussel_photo_bandeau.html.twig', [
            'formCarroussel' => $formCarroussel->createView(),
            'titre' => 'Carroussel bas',
            'taille' => 'Dimensions: 1200 x 280'
           
        ]);  
    }
    /**
     * @Route("/admin/carroussel_bas/img_rem/{id}", name="carroussel__bas_rem")
     * @return Response
     */
    public function carroussel_bas_rem($id, ContentRepository $contentrepository, Request $request, EntityManager $em, Random $random){

        $nouvelle_image = $contentrepository->find($id);

        $em=$this->getDoctrine()->getManager();
        $em->remove($nouvelle_image);
        $em->flush();

        $this->addFlash('success','Votre contenu a bien été supprimé.');
        return $this->redirectToRoute('carroussel_bas');
        }
}
