<?php

namespace App\Controller\Admin;

use App\Entity\Podcast;
use App\Form\PodcastType;
use App\Controller\HelperTrait;
use App\Services\random;
use App\Repository\PodcastRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface as EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AdminPodcastController extends AbstractController
{
    use HelperTrait;
    /**
     * @Route("/admin/podcast", name="admin_podcast")
     */
    public function index(PodcastRepository $podcastRepository)
    {

        return $this->render('admin/pages/podcast.html.twig', [
            'controller_name' => 'AdminPodcastController',
            'podcast' => $podcastRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/podcast/delete/{id}", name="delete_podcast")
     */
    public function delete($id, PodcastRepository $podcastRepository, EntityManager $em)
    {
        $podcast = $podcastRepository->find($id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($podcast);
        $em->flush();

        $this->addFlash('success','Le podcast a bien été supprimé.');
        return $this->redirectToRoute('admin_podcast');

    }
    /**
     * @Route("/admin/podcast/new", name="new_podcast")
     */
    public function new_podcast(PodcastRepository $podcastRepository, Request $request, EntityManager $em, Random $random)
    {
        $nouveau_podcast = new Podcast;

        $form = $this->createForm(PodcastType::class, $nouveau_podcast)
            ->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
          
            $audio = $form['audio']->getData();
            if($audio){
                $fileName = $this->slugify($random->random(10)).'.'.$audio->guessExtension();
                    // Move the file to the directory where brochures are stored
                    try {
                        $audio->move($this->getParameter('audio_assets_dir'), $fileName);
                    } catch (FileException $e) {
                        die("Une erreur s'est produite lors du téléchargement de l'image sur le serveur veuillez réessayer ultérieurement.");
                    }
                    $nouveau_podcast->setAudio($fileName);
            } 
            
            $photo = $form['image']->getData();

            if($photo){
                $fileName = $this->slugify($random->random(10)).'.'.$photo->guessExtension();
                    // Move the file to the directory where brochures are stored
                    try {
                        $photo->move($this->getParameter('podcast_assets_dir'), $fileName);
                    } catch (FileException $e) {
                        die("Une erreur s'est produite lors du téléchargement de l'image sur le serveur veuillez réessayer ultérieurement.");
                    }
                    $nouveau_podcast->setImage($fileName);
            } else {
                    $nouveau_podcast->setImage('d.jpeg');
                }

            $em->persist($nouveau_podcast);
            $em->flush();

            $this->addFlash('success','Votre contenu a bien été mis à jour.');

            return $this->redirectToRoute('admin_podcast');
        }

        return $this->render('admin/pages/podcast_new.html.twig', [
            'form' => $form->createView(),
            
        ]);
    }

    /**
     * @Route("/admin/podcast/edit/{id}", name="edit_podcast")
     */
    public function edit_podcast($id, PodcastRepository $podcastRepository, Request $request, EntityManager $em, Random $random)
    {
        $nouveau_podcast = $podcastRepository->find($id);

        $form = $this->createForm(PodcastType::class, $nouveau_podcast)
            ->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
          
            $audio = $form['audio']->getData();
            if($audio){
                $fileName = $this->slugify($random->random(10)).'.'.$audio->guessExtension();
                    // Move the file to the directory where brochures are stored
                    try {
                        $audio->move($this->getParameter('audio_assets_dir'), $fileName);
                    } catch (FileException $e) {
                        die("Une erreur s'est produite lors du téléchargement de l'image sur le serveur veuillez réessayer ultérieurement.");
                    }
                    $nouveau_podcast->setAudio($fileName);
            } 
            
            $photo = $form['image']->getData();

            if($photo){
                $fileName = $this->slugify($random->random(10)).'.'.$photo->guessExtension();
                    // Move the file to the directory where brochures are stored
                    try {
                        $photo->move($this->getParameter('podcast_assets_dir'), $fileName);
                    } catch (FileException $e) {
                        die("Une erreur s'est produite lors du téléchargement de l'image sur le serveur veuillez réessayer ultérieurement.");
                    }
                    $nouveau_podcast->setImage($fileName);
                }

            $em->persist($nouveau_podcast);
            $em->flush();

            $this->addFlash('success','Votre contenu a bien été mis à jour.');

            return $this->redirectToRoute('admin_podcast');
        }

        return $this->render('admin/pages/podcast_new.html.twig', [
            'form' => $form->createView(),
            'titre' => 'Modifier Podcast'
            
        ]);
    }
}
