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

class BandeauxController extends AbstractController
{    
    use HelperTrait;
    /**
     * @Route("/admin/carroussel_bandeau/{bandeau_cle}", name="carroussel_bandeau")
     * @return Response
     */
    public function bandeau($bandeau_cle, ContentRepository $contentrepository, Request $request, EntityManager $em){

        $bandeau = $contentrepository->findByCle($bandeau_cle);

        $bandeau_first = $contentrepository->findByCle($bandeau_cle . '_first');
  
        return $this->render('admin/pages/carroussel_bandeau.html.twig', [
            'bandeau'=> $bandeau,
            'titre' => $bandeau_cle, 
            'first_image' => $bandeau_first[0]
           
        ]);  
    }

    /**
     * @Route("/admin/carroussel_bandeau/edit/{bandeau_cle}/{id}", name="carroussel_bandeau_edit")
     * @return Response
     */
    public function carroussel_bandeau_edit($id, $bandeau_cle, ContentRepository $contentrepository, Request $request, EntityManager $em, Random $random){

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
            return $this->redirectToRoute('carroussel_bandeau', ['bandeau_cle' => $bandeau_cle]);
        }

        return $this->render('admin/pages/carroussel_photo_bandeau.html.twig', [
            'formCarroussel' => $formCarroussel->createView(),
            'titre' => 'Modifier l\'image'
           
        ]);  
        }

    /**
     * @Route("/admin/carroussel_bandeau/img_add/{bandeau}", name="carroussel_bandeau_add")
     * @return Response
     */
    public function carroussel_add($bandeau, ContentRepository $contentrepository, Request $request, EntityManager $em, Random $random){

        $nouvelle_image = new Content;
        $formCarroussel = $this->createForm(ContentphotoType::class, $nouvelle_image)
            ->handleRequest($request);
        if($formCarroussel->isSubmitted() && $formCarroussel->isValid()){

        $image = $formCarroussel['file']->getData();
        if($image){
            $fileName = $this->slugify($random->random(10)).'.'.$image->guessExtension();

            $image->move($this->getParameter('illustration_assets_dir'), $fileName);
        }
        
        $nouvelle_image->setCle($bandeau);
        $nouvelle_image->setContent($fileName);
        $em=$this->getDoctrine()->getManager();
        $em->persist($nouvelle_image);
        $em->flush();

        $this->addFlash('success','Votre contenu a bien été ajouté.');
        return $this->redirectToRoute('carroussel_bandeau', ['bandeau_cle' => $bandeau]);
        }

        return $this->render('admin/pages/carroussel_photo.html.twig', [
            'formCarroussel' => $formCarroussel->createView(),
            'titre' => 'Ajouter image',
           
        ]);  
        }

    /**
     * @Route("/admin/carroussel_bandeau/remove/{bandeau_cle}/{id}", name="carroussel_bandeau_rem")
     * @return Response
     */
    public function carroussel_rem($id, $bandeau_cle, ContentRepository $contentrepository, Request $request, EntityManager $em, Random $random){

        $nouvelle_image = $contentrepository->find($id);

        $em=$this->getDoctrine()->getManager();
        $em->remove($nouvelle_image);
        $em->flush();

        $this->addFlash('success','Votre contenu a bien été supprimé.');
        return $this->redirectToRoute('carroussel_bandeau', ['bandeau_cle' => $bandeau_cle]);
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
