<?php

namespace App\Controller\Admin;


use App\Entity\Livres;
use App\Form\LivreFormType;
use App\Repository\CommentRepository;
use App\Repository\LivresRepository;
use App\Services\random;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AdminLivreController extends AbstractController
{


    /**
     * @Route("/admin/livres", name="admin_livre")
     * @param LivresRepository $livresRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listBooks(LivresRepository $livresRepository) {

        return $this->render("admin/pages/listbooks.html.twig", [
            'livres' => $livresRepository->findBy([], ['CreatedAt' => 'DESC']),
            'countLivre' => $livresRepository->countLivres()
        ]);

    }

    /**
     * @Route("/admin/add/livre", name="admin_add_livre")
     * @param Request $request
     * @param random $random
     * @param ObjectManager $em
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addBook(Request $request, random $random, ObjectManager $em) {

        $livre = new Livres();

        // dump($request->getUser());

        $form = $this->createForm(LivreFormType::class, $livre)
            ->handleRequest($request);

        if ($form->isSubmitted()&& $form->isValid()){

            $photo = $livre->getPhoto();

            $fileName = $livre->getSlug().'.'.$photo->guessExtension();

            // Move the file to the directory where brochures are stored
            try {
                $photo->move(
                    $this->getParameter('livre_assets_dir'),
                    $fileName
                );
            } catch (FileException $e) {
                die("Une erreur s'est produite lors du téléchargement de l'image sur le serveur !");
            }
            //Mise à jour de l'image
            $livre->setPhoto($fileName);

            $livre->setUser($this->getUser());

            //Sauvegarde en BDD
            $em=$this->getDoctrine()->getManager();
            $em->persist($livre);
            $em->flush();

            $this->addFlash('success','Le livre a bien été ajouté');

            return $this->redirectToRoute('admin_livre');
        }

        return $this->render("admin/pages/addbook.html.twig", [
            'form' => $form->createView(),
            'user' => $request->getUser()
        ]);

    }

    /**
     * @Route("/admin/edit/livre/{id}", name="admin_edit_livre")
     * @param Livres $livre
     * @param Request $request
     * @param random $random
     * @param ObjectManager $em
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editbook(Livres $livre, Request $request, random $random, ObjectManager $em) {

        $form = $this->createForm(LivreFormType::class, $livre)
            ->handleRequest($request);

        if ($form->isSubmitted()&& $form->isValid()){

            if($livre->getPhoto()!=null){
                $photo = $livre->getPhoto();

                $fileName = $random->random(10).'.'.$photo->guessExtension();

                try {
                    $photo->move(
                        $this->getParameter('livre_assets_dir'),
                        $fileName
                    );
                } catch (FileException $e) {
                    die("Une erreur s'est produite lors du téléchargement de l'image sur le serveur !");
                }

                $livre->setPhoto($fileName);
            } else{
                $livre->setPhoto($request->get('temporary'));
            }

            //Sauvegarde en BDD
            $em=$this->getDoctrine()->getManager();
            $em->persist($livre);
            $em->flush();

            $this->addFlash('success','Les modifications effectuées sur le livre ont été enregistré !');

            return $this->redirectToRoute('admin_livre');
        }

        return $this->render("admin/pages/editbook.html.twig", [
            'form' => $form->createView(),
            'livre' => $livre
        ]);

    }

    /**
     * @Route("/admin/delete/livre/{id}", name="admin_delete_livre", methods="DELETE")
     * @param Livres $livre
     * @param Request $request
     * @param ObjectManager $em
     * @param CommentRepository $commentRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete_book(Livres $livre, Request $request, ObjectManager $em, CommentRepository $commentRepository) {

        $deleteComment = $commentRepository->findBy(['livre' => $livre]);

        if($this->isCsrfTokenValid('delete' . $livre->getId(), $request->get("_token"))) {
            $deleteComment = $commentRepository->findBy(['livre' => $livre]);

            if($deleteComment) {
                foreach ($deleteComment as $comment) {
                    $em->remove($comment);
                }
            }
            $em->remove($livre);
            $em->flush();
            $this->addFlash("success", "Le livre à bien été supprimer");
        }

        return $this->redirectToRoute("admin_livre");

    }


}
