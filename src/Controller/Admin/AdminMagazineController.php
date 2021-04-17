<?php

namespace App\Controller\Admin;

use App\Controller\HelperTrait;
use App\Entity\ArticlesMag;
use App\Form\ArticlesMagType;
use App\Form\MagcontentsType;
use App\Form\MagContenueType;
use App\Form\MagImgUnType;
use App\Form\MagContenueUnType;
use App\Form\MagContenuSommType;
use App\Form\MagContenuSomm2Type;
use App\Form\MagContenuGdTreType;
use App\Form\MagImgDeuxType;
use App\Form\MagImgTroisType;
use App\Form\MagImgType;
use App\Entity\MagContents;
use App\Services\random;
use App\Repository\ArticlesMagRepository;
use App\Repository\MagContentsRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class AdminMagazineController extends AbstractController
{
    use HelperTrait;

    /**
     * @Route("/admin/magazine", name="admin_magazine")
     * @param Request $request
     * @param ObjectManager $em
     * @param ArticlesMagRepository $ArticlesMagRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function AjoutArticle(Request $request, ArticlesMagRepository $ArticlesMagRepository , MagContentsRepository $MagContentsRepository, ObjectManager $em, Random $random)
    {

        $articles = new ArticlesMag();
        $MagContents= new MagContents();

        $bloc_sommaire = $MagContentsRepository->findByCle('Sommaire');
        $bloc_sommaire1 = $MagContentsRepository->findByCle('Sommaire1');
        $bloc_edition = $MagContentsRepository->findByCle('Edition');
        $bloc_dosspec = $MagContentsRepository->findByCle('DosSpec');
        $bloc_titre = $MagContentsRepository->findByCle('TitreB1_2');
        $bloc_gdtitre = $MagContentsRepository->findByCle('GrandTitre');

        $listeImage = $MagContentsRepository->findByCle('GrandeBan');
        $imageB1 = $MagContentsRepository->findByCle('ImageB1_2');
        $imageB2 = $MagContentsRepository->findByCle('ImageB1_3');
        $imageB3 = $MagContentsRepository->findByCle('ImageB2_1');

        
        
        // Gestion des articles
        $form = $this->createForm(ArticlesMagType::class, $articles);
        $form->handleRequest($request);
        $name = $this->slugify($random->random(5));

        if($form->isSubmitted() && $form->isValid()) {

            $image = $form['imagArt']->getData();
            if($image){
                $fileName = $this->slugify($random->random(10)).'.'.$image->guessExtension();

                $image->move($this->getParameter('magazine_assets_dir'), $fileName);
            }     
            $articles->setCle($name);
            $articles->setImagART($fileName);
            $em = $this->getDoctrine()->getManager();
            $em->persist($articles);
            $em->flush();
            $this->addFlash('success', 'L\'article '.$articles->getTitles().' a bien été ajouté');
            return $this->redirectToRoute('admin_magazine');
        }

        // Gestion page Magazine
        $formGrandeBan = $this->createForm(MagImgType::class, $MagContents);
        $formGrandeBan->handleRequest($request);

        if($formGrandeBan->isSubmitted() && $formGrandeBan->isValid()){

            $image = $formGrandeBan['imagMag']->getData();
            if($image){
                $fileName = $this->slugify($random->random(10)).'.'.$image->guessExtension();

                $image->move($this->getParameter('magazine_assets_dir'), $fileName);
            }     
            $MagContents->setCle('GrandeBan');
            $MagContents->setImagMag($fileName);
            $em->persist($MagContents);
            $em=$this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success','Votre image a bien été enregistrée.');

            return $this->redirectToRoute('admin_magazine');
        }

        //    Edition
        $formEdition = $this->createForm(MagcontentsType::class, $MagContents);
        $formEdition->handleRequest($request);

        if($formEdition->isSubmitted() && $formEdition->isValid()){

            $em=$this->getDoctrine()->getManager();
            $MagContents->setCle('Edition');
            $em->persist($MagContents);
            $em->flush();

            $this->addFlash('success','Votre contenu a bien été mis à jour.');

            return $this->redirectToRoute('admin_magazine');
        }

        //   Titre dossier special
        $formDosSpec = $this->createForm(MagContenueType::class, $MagContents);
        $formDosSpec->handleRequest($request);

        if($formDosSpec->isSubmitted() && $formDosSpec->isValid()){

            $em=$this->getDoctrine()->getManager();
            $MagContents->setCle('DosSpec');
            $em->persist($MagContents);
            $em->flush();

            $this->addFlash('success','Votre contenu a bien été mis à jour.');

            return $this->redirectToRoute('admin_magazine');
        }

        //   image bloc 1_2
        $formImg1 = $this->createForm(MagImgUnType::class, $MagContents);
        $formImg1->handleRequest($request);

        if($formImg1->isSubmitted() && $formImg1->isValid()){

            $image = $formImg1['imagMag']->getData();
            if($image){
                $fileName = $this->slugify($random->random(10)).'.'.$image->guessExtension();

                $image->move($this->getParameter('magazine_assets_dir'), $fileName);
            }     
            $MagContents->setCle('ImageB1_2');
            $MagContents->setImagMag($fileName);
            $em->persist($MagContents);
            $em=$this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success','Votre contenu a bien été mis à jour.');

            return $this->redirectToRoute('admin_magazine');
        }

        //  titre bloc 1_2 
        $formTitreImg1 = $this->createForm(MagContenueUnType::class, $MagContents);
        $formTitreImg1->handleRequest($request);

        if($formTitreImg1->isSubmitted() && $formTitreImg1->isValid()){

            $em=$this->getDoctrine()->getManager();
            $MagContents->setCle('TitreB1_2');
            $em->persist($MagContents);
            $em->flush();

            $this->addFlash('success','Votre contenu a bien été mis à jour.');

            return $this->redirectToRoute('admin_magazine');
        }

        //  image bloc 1_3 
        $formImg2 = $this->createForm(MagImgDeuxType::class, $MagContents);
        $formImg2->handleRequest($request);

        if($formImg2->isSubmitted() && $formImg2->isValid()){

            $image = $formImg2['imagMag']->getData();
            if($image){
                $fileName = $this->slugify($random->random(10)).'.'.$image->guessExtension();

                $image->move($this->getParameter('magazine_assets_dir'), $fileName);
            }     
            $MagContents->setCle('ImageB1_3');
            $MagContents->setImagMag($fileName);
            $em->persist($MagContents);
            $em=$this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success','Votre contenu a bien été mis à jour.');

            return $this->redirectToRoute('admin_magazine');
        }

        //  grand titre
        $formGdTitre = $this->createForm(MagContenuGdTreType::class, $MagContents);
        $formGdTitre->handleRequest($request);

        if($formGdTitre->isSubmitted() && $formGdTitre->isValid()){

            $em=$this->getDoctrine()->getManager();
            $MagContents->setCle('GrandTitre');
            $em->persist($MagContents);
            $em->flush();

            $this->addFlash('success','Votre contenu a bien été mis à jour.');

            return $this->redirectToRoute('admin_magazine');
        }

        //  image bloc 2_1 
        $formImg3 = $this->createForm(MagImgTroisType::class, $MagContents);
        $formImg3->handleRequest($request);

        if($formImg3->isSubmitted() && $formImg3->isValid()){

            $image = $formImg3['imagMag']->getData();
            if($image){
                $fileName = $this->slugify($random->random(10)).'.'.$image->guessExtension();

                $image->move($this->getParameter('magazine_assets_dir'), $fileName);
            }     
            $MagContents->setCle('ImageB2_1');
            $MagContents->setImagMag($fileName);
            $em->persist($MagContents);
            $em=$this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success','Votre contenu a bien été mis à jour.');

            return $this->redirectToRoute('admin_magazine');
        }

        //  sommaire
        $formSomm = $this->createForm(MagcontenuSomm2Type::class, $MagContents);
        $formSomm->handleRequest($request);

        if($formSomm->isSubmitted() && $formSomm->isValid()){

            $em=$this->getDoctrine()->getManager();
            $MagContents->setCle('Sommaire');
            $em->persist($MagContents);
            $em->flush();

            $this->addFlash('success','Votre contenu a bien été mis à jour.');

            return $this->redirectToRoute('admin_magazine');
        }

        

        return $this->render('admin/pages/editMags.html.twig', [
            'form' => $form->createView(),
            'articles' => $ArticlesMagRepository->findBy([], ['created_at' => 'DESC']),
            'formGrandeBan' => $formGrandeBan->createView(),
            'formEdition' => $formEdition->createView(),
            'formDosSpec' => $formDosSpec->createView(),
            'formImg1' => $formImg1->createView(),
            'formTitreImg1' => $formTitreImg1->createView(),
            'formImg2' => $formImg2->createView(),
            'formGdTitre' => $formGdTitre->createView(),
            'formImg3' => $formImg3->createView(),
            'formSomm' => $formSomm->createView(),
            'puce' => $bloc_sommaire,
            
            'magText' => $bloc_edition,
            'magTextspec' => $bloc_dosspec,
            'magTexttitre' => $bloc_titre,
            'magTextgdtitre' => $bloc_gdtitre,
            'magContents' => $listeImage,
            'magContents1' => $imageB1,
            'magContents2' => $imageB2,
            'magContents3' => $imageB3          
        ]);

    }

    /**
     * @Route("/admin/puce/delete/{id}", name="delete-puce")
     * @return Response
     */
    public function deletepuce($id, MagContentsRepository $MagContentsRepository , Request $request, ObjectManager $em){
        $puce = $MagContentsRepository->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($puce);
        $em->flush();

        $this->addFlash('success','Votre ligne a bien été supprimée.');
        return $this->redirectToRoute('admin_magazine');
    }

    /**
     * @Route("/admin/puce/{id}", name="edit-puce")
     * @return Response
     */
    public function puce($id, MagContentsRepository $MagContentsRepository, Request $request, ObjectManager $em){
        
        $puce = $MagContentsRepository->find($id);

        $formpucesomm = $this->createForm(MagcontenuSomm2Type::class, $puce)
            ->handleRequest($request);

        if($formpucesomm->isSubmitted() && $formpucesomm->isValid()){

            $em=$this->getDoctrine()->getManager();
            $em->persist($puce);
            $em->flush();

            $this->addFlash('success','Votre sommaire a bien été mis à jour.');

            return $this->redirectToRoute('admin_magazine');
        }

        return $this->render('admin/pages/formpucesomm.html.twig', [
            'formpucesomm' => $formpucesomm->createView(),
           
        ]);  
    }

    
    /**
     * @Route("/admin/edition/{id}", name="edit-edition")
     * @return Response
     */
    public function edition($id, MagContentsRepository $MagContentsRepository, Request $request, ObjectManager $em){
        
        $puce = $MagContentsRepository->find($id);

        $formpucesomm = $this->createForm(MagcontenuSommType::class, $puce)
            ->handleRequest($request);

        if($formpucesomm->isSubmitted() && $formpucesomm->isValid()){

            $em=$this->getDoctrine()->getManager();
            $em->persist($puce);
            $em->flush();

            $this->addFlash('success','Votre sommaire a bien été mis à jour.');

            return $this->redirectToRoute('admin_magazine');
        }

        return $this->render('admin/pages/formText.html.twig', [
            'formpucesomm' => $formpucesomm->createView(),
           
        ]);  
    }


    /**
     * @Route("/admin/textspec/{id}", name="edit-textspec")
     * @return Response
     */
    public function textspec($id, MagContentsRepository $MagContentsRepository, Request $request, ObjectManager $em){
        
        $puce = $MagContentsRepository->find($id);

        $formpucesomm = $this->createForm(MagcontenuSommType::class, $puce)
            ->handleRequest($request);

        if($formpucesomm->isSubmitted() && $formpucesomm->isValid()){

            $em=$this->getDoctrine()->getManager();
            $em->persist($puce);
            $em->flush();

            $this->addFlash('success','Votre sommaire a bien été mis à jour.');

            return $this->redirectToRoute('admin_magazine');
        }

        return $this->render('admin/pages/formText.html.twig', [
            'formpucesomm' => $formpucesomm->createView(),
           
        ]);  
    }

        /**
     * @Route("/admin/texttitre/{id}", name="edit-texttitre")
     * @return Response
     */
    public function texttitre($id, MagContentsRepository $MagContentsRepository, Request $request, ObjectManager $em){
        
        $puce = $MagContentsRepository->find($id);

        $formpucesomm = $this->createForm(MagcontenuSommType::class, $puce)
            ->handleRequest($request);

        if($formpucesomm->isSubmitted() && $formpucesomm->isValid()){

            $em=$this->getDoctrine()->getManager();
            $em->persist($puce);
            $em->flush();

            $this->addFlash('success','Votre sommaire a bien été mis à jour.');

            return $this->redirectToRoute('admin_magazine');
        }

        return $this->render('admin/pages/formText.html.twig', [
            'formpucesomm' => $formpucesomm->createView(),
           
        ]);  
    }

    /**
     * @Route("/admin/gdtitre/{id}", name="edit-gdtitre")
     * @return Response
     */
    public function gdtitre($id, MagContentsRepository $MagContentsRepository, Request $request, ObjectManager $em){
        
        $puce = $MagContentsRepository->find($id);

        $formpucesomm = $this->createForm(MagcontenuSommType::class, $puce)
            ->handleRequest($request);

        if($formpucesomm->isSubmitted() && $formpucesomm->isValid()){

            $em=$this->getDoctrine()->getManager();
            $em->persist($puce);
            $em->flush();

            $this->addFlash('success','Votre sommaire a bien été mis à jour.');

            return $this->redirectToRoute('admin_magazine');
        }

        return $this->render('admin/pages/formText.html.twig', [
            'formpucesomm' => $formpucesomm->createView(),
           
        ]);  
    }

    /**
     * @Route("admin/edit-article/{id}", name="admin_article_edit")
     * @param ObjectManager $em
     * @param Request $request
     * @param ArticlesMag $ArticlesMag
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit_article(ObjectManager $em, Request $request, ArticlesMag $articlesMag, Random $random) {

        $editArticle = $this->createForm("App\Form\ArticlesMagType", $articlesMag);
        $editArticle->handleRequest($request);
        $name = $this->slugify($random->random(5));

        if($editArticle->isSubmitted() && $editArticle->isValid()) {

            //Sauvegarde en BDD
            $image = $editArticle['imagArt']->getData();
            if($image){
                $fileName = $this->slugify($random->random(10)).'.'.$image->guessExtension();

                $image->move($this->getParameter('magazine_assets_dir'), $fileName);
            }     
            $articlesMag->setCle($name);
            $articlesMag->setImagART($fileName);
            $em=$this->getDoctrine()->getManager();
            $em->persist($articlesMag);
            $em->flush();
            // Notification
            $this->addFlash('success','L\'article a bien été mis à jour !');
            return $this->redirectToRoute('admin_magazine');
        }


        return $this->render("admin/pages/editArticlesMag.html.twig", [
            'form' => $editArticle->createView(),
            'articlesMag' => $articlesMag
        ]);

    }

     /**
     * @Route("admin/delete/articles/{id}", name="admin_articles_delete", methods={"DELETE"})
     * @param Request $request
     * @param ObjectManager $em
     * @param ArticlesMag $ArticlesMag
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete_edito(Request $request, ObjectManager $em, ArticlesMag $articlesMag) {

        if($this->isCsrfTokenValid('delete' . $articlesMag->getId(), $request->get("_token"))) {
            $em->remove($articlesMag);
            $em->flush();
            $this->addFlash('success', 'l\'article '.$articlesMag->getTitles().' a été supprimé avec succès');
        }

        return $this->redirectToRoute("admin_magazine");

    }

        /**
         * @Route("articles/{slug}/{id}", name="articles_view", methods={"GET"}, requirements={"slug": "[a-z0-9\-]*"})
         * @param ArticlesMag $ArticlesMag
         * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
         */
        public function articles_view(ArticlesMag $articlesMag){

            return $this->render("pages/articles-view.html.twig", [
                'current_menu' => 'articlesMag',
                'articlesMag' => $articlesMag,
            ]);

        }

    /**
     * @Route("/admin/edit-image/{id}", name="admin_edit_image", methods={"GET", "POST"})
     * @param ObjectManager $em
     * @param Request $request
     * @param MagContents $MagContents
     * @param MagContentsRepository $MagContentsRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit_image(ObjectManager $em, Request $request, MagContentsRepository $MagContentsRepository, MagContents $MagContents, random $random) {


        $editImage = $this->createForm(MagImgType::class, $MagContents);
        $editImage->handleRequest($request);

        if($editImage->isSubmitted() && $editImage->isValid()) {

            $image = $editImage['imagMag']->getData();
            if($image){
                $fileName = $this->slugify($random->random(10)).'.'.$image->guessExtension();

                $image->move($this->getParameter('magazine_assets_dir'), $fileName);
            }     
            $MagContents->setCle('GrandeBan');
            $MagContents->setImagMag($fileName);
            $em->persist($MagContents);
            $em->flush();

            // Notification
            $this->addFlash('success','votre image a bien été mise à jour !');
            return $this->redirectToRoute('admin_magazine');
        }

        return $this->render("admin/pages/edit-image.html.twig", [
            'form' => $editImage->createView(),
            'image' => $MagContents,
        ]);

    }

    /**
     * @Route("/admin/edit-image1/{id}", name="admin_edit_image1", methods={"GET", "POST"})
     * @param ObjectManager $em
     * @param Request $request
     * @param MagContents $MagContents
     * @param MagContentsRepository $MagContentsRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit_image1(ObjectManager $em, Request $request, MagContentsRepository $MagContentsRepository, MagContents $MagContents, random $random) {


        $editImage = $this->createForm(MagImgUnType::class, $MagContents);
        $editImage->handleRequest($request);

        if($editImage->isSubmitted() && $editImage->isValid()) {

            $image = $editImage['imagMag']->getData();
            if($image){
                $fileName = $this->slugify($random->random(10)).'.'.$image->guessExtension();

                $image->move($this->getParameter('magazine_assets_dir'), $fileName);
            }     
            $MagContents->setCle('ImageB1_2');
            $MagContents->setImagMag($fileName);
            $em->persist($MagContents);
            $em->flush();

            // Notification
            $this->addFlash('success','votre image a bien été mise à jour !');
            return $this->redirectToRoute('admin_magazine');
        }

        return $this->render("admin/pages/edit-image.html.twig", [
            'form' => $editImage->createView(),
            'image' => $MagContents,
        ]);

    }

     /**
     * @Route("/admin/edit-image2/{id}", name="admin_edit_image2", methods={"GET", "POST"})
     * @param ObjectManager $em
     * @param Request $request
     * @param MagContents $MagContents
     * @param MagContentsRepository $MagContentsRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit_image2(ObjectManager $em, Request $request, MagContentsRepository $MagContentsRepository, MagContents $MagContents, random $random) {


        $editImage = $this->createForm(MagImgDeuxType::class, $MagContents);
        $editImage->handleRequest($request);

        if($editImage->isSubmitted() && $editImage->isValid()) {

            $image = $editImage['imagMag']->getData();
            if($image){
                $fileName = $this->slugify($random->random(10)).'.'.$image->guessExtension();

                $image->move($this->getParameter('magazine_assets_dir'), $fileName);
            }     
            $MagContents->setCle('ImageB1_3');
            $MagContents->setImagMag($fileName);
            $em->persist($MagContents);
            $em->flush();

            // Notification
            $this->addFlash('success','votre image a bien été mise à jour !');
            return $this->redirectToRoute('admin_magazine');
        }

        return $this->render("admin/pages/edit-image.html.twig", [
            'form' => $editImage->createView(),
            'image' => $MagContents,
        ]);

    }

     /**
     * @Route("/admin/edit-image3/{id}", name="admin_edit_image3", methods={"GET", "POST"})
     * @param ObjectManager $em
     * @param Request $request
     * @param MagContents $MagContents
     * @param MagContentsRepository $MagContentsRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit_image3(ObjectManager $em, Request $request, MagContentsRepository $MagContentsRepository, MagContents $MagContents, random $random) {


        $editImage = $this->createForm(MagImgTroisType::class, $MagContents);
        $editImage->handleRequest($request);

        if($editImage->isSubmitted() && $editImage->isValid()) {

            $image = $editImage['imagMag']->getData();
            if($image){
                $fileName = $this->slugify($random->random(10)).'.'.$image->guessExtension();

                $image->move($this->getParameter('magazine_assets_dir'), $fileName);
            }     
            $MagContents->setCle('ImageB2_1');
            $MagContents->setImagMag($fileName);
            $em->persist($MagContents);
            $em->flush();

            // Notification
            $this->addFlash('success','votre image a bien été mise à jour !');
            return $this->redirectToRoute('admin_magazine');
        }

        return $this->render("admin/pages/edit-image.html.twig", [
            'form' => $editImage->createView(),
            'image' => $MagContents,
        ]);

    }

    /**
     * @Route("/admin/delete-image/{id}", name="admin_delete_image", methods="DELETE")
     * @param $id
     * @param MagContents $MagContents
     * @param Request $request
     * @param ObjectManager $em
     * @param MagContentsRepository $MagContentsRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete_image($id, MagContents $MagContents, Request $request, ObjectManager $em, MagContentsRepository $MagContentsRepository) {

        $deleteImage = $MagContentsRepository->findBy(['cle' => $id]);

        if($this->isCsrfTokenValid('delete' . $MagContents->getId(), $request->get("_token"))) {
            if($deleteImage) {
                foreach ($deleteImage as $images) {
                    $em->remove($images);
                }
            }
            $em->remove($MagContents);
            $em->flush();
            $this->addFlash("success", "Votre image à bien ete supprimée");
        }

        return $this->redirectToRoute("admin_magazine");

    }


}