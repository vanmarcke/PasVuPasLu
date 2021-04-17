<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\NewsType;
use App\Entity\News;
use App\Repository\NewsRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Services\random;
use Doctrine\Common\Persistence\ObjectManager;
use App\Controller\HelperTrait;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AdminNewsController extends AbstractController
{
    use HelperTrait;


     /**
      * @Route("/admin/news", name="admin_news")
      * @param NewsRepository $newsRepository
      * @return \Symfony\Component\HttpFoundation\Response
      */
     public function listNews(NewsRepository $newsRepository) {

        return $this->render("admin/pages/listnews.html.twig", [
             'news' => $newsRepository->findBy([], ['createAt' => 'DESC']),
             'countnews' => $newsRepository->countNews(),
         ]);

     }



     /**
      * @Route("/admin/news/add", name="add_news")
      * @param Request $request
      * @param random $random
      * @param ObjectManager $em
      * @return \Symfony\Component\HttpFoundation\Response
      */
     public function addnews(Request $request, random $random, ObjectManager $em)
    {   
         $new = new news;
         $form = $this->createForm(NewsType::class, $new)
         ->handleRequest($request);

         if ($form->isSubmitted()&& $form->isValid()){

                 /** @var UploadedFile $image*/
                 $photo = $form['photo']->getData();
                 if($photo){
                    $fileName = $this->slugify($random->random(10)).'.'.$photo->guessExtension();
                         // Move the file to the directory where brochures are stored
                         try {
                            $photo->move($this->getParameter('news_assets_dir'), $fileName);
                         } catch (FileException $e) {
                             die("Une erreur s'est produite lors du téléchargement de l'image sur le serveur veuillez réessayer ultérieurement.");
                         }
                         $new->setPhoto($fileName);
                 } else {
                         $new->setPhoto('d.png');
                     }

             //Sauvegarde en BDD
             $em=$this->getDoctrine()->getManager();
             $em->persist($new);
             $em->flush();

             $this->addFlash('success','Cette actualité a bien été ajouté');

             return $this->redirectToRoute('admin_news');
            } 

         return $this->render('admin/pages/addnews.html.twig', [
             'controller_name' => 'AdminNewsController',
             'form' => $form->createView(),
         ]);
    }


    /**
     * @Route("/admin/news/edit/{id}", name="edit_news")
     * @param News $new
     * @param Request $request
     * @param random $random
     * @param ObjectManager $em
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editNews(News $new, Request $request, random $random, ObjectManager $em) {

        $form = $this->createForm(NewsType::class, $new)
            ->handleRequest($request);

        if ($form->isSubmitted()&& $form->isValid()){

              /** @var UploadedFile $image*/
              $photo = $form['photo']->getData();
              if($photo){
                  $fileName = $this->slugify($random->random(10)).'.'.$photo->guessExtension();
                      // Move the file to the directory where brochures are stored
                      try {
                          $photo->move($this->getParameter('news_assets_dir'), $fileName);
                      } catch (FileException $e) {
                          die("Une erreur s'est produite lors du téléchargement de l'image sur le serveur veuillez réessayer ultérieurement.");
                      }
                      $new->setPhoto($fileName);
              } else {
                $new->setPhoto($request->get('temporary'));
                  }


            //Sauvegarde en BDD
            $em=$this->getDoctrine()->getManager();
            $em->persist($new);
            $em->flush();

            $this->addFlash('success','Les modifications ont bien été enregistrées !');

            return $this->redirectToRoute('admin_news');
        }

        return $this->render("admin/pages/editnews.html.twig", [
            'form' => $form->createView(),
            'new' => $new
        ]);

    }

     /**
     * @Route("/admin/news/delete/{id}", name="delete_news", methods="DELETE")
     * @param News new
     * @param Request $request
     * @param ObjectManager $em
     * @param CommentRepository $commentRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete_news(News $new, Request $request, ObjectManager $em) {

        

        if($this->isCsrfTokenValid('delete' . $new->getId(), $request->get("_token"))) {
            
            $em->remove($new);
            $em->flush();
            $this->addFlash("success", "Cette actualité a bien été supprimer");
        }

        return $this->redirectToRoute("admin_news");

    }

}
