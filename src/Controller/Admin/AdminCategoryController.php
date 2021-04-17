<?php

namespace App\Controller\Admin;

use App\Controller\HelperTrait;
use App\Entity\Categories;
use App\Form\CategorieFormType;
use App\Services\random;
use App\Repository\CategoriesRepository;
use App\Repository\CommentRepository;
use App\Repository\LivresRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class AdminCategoryController extends AbstractController
{
    use HelperTrait;


    /**
     * @Route("/admin/category", name="admin_category")
     * @param Request $request
     * @param Categories|null $categories
     * @param CategoriesRepository $categoriesRepository
     * @return
     */
    public function AjouterCategorie(Request $request, Categories $categories=null, CategoriesRepository $categoriesRepository )
    {

        if (!$categories){
            $categories = new Categories();

        }

//        Création du Formulaire
        $form = $this->createForm(CategorieFormType::class, $categories)
            ->handleRequest($request);

//         Si le formulaire est soumis et valide
        if ($form->isSubmitted()&& $form->isValid()){
            $photo = $categories->getPhoto();

            $fileName = $this->slugify($categories->getCategorie()).'.'.$photo->guessExtension();

            // Move the file to the directory where brochures are stored
            try {
                $photo->move(
                    $this->getParameter('categorie_assets_dir'),
                    $fileName
                );
            } catch (FileException $e) {
                die("Une erreur s'est produite lors du téléchargement de l'image sur le serveur !");
            }
            //Mise à jour de l'image
            $categories->setPhoto($fileName);



            //Sauvegarde en BDD
            $em=$this->getDoctrine()->getManager();
            $em->persist($categories);
            $em->flush();

            // Notification
            $this->addFlash('success',
                'La catégorie a bien été ajouté!');

//            //Rediraction
            return $this->redirectToRoute('admin_category');
        }

        $listCategorie = $categoriesRepository->findAll();

//    Affichage dans la vue
        return $this->render('admin/pages/category.html.twig', [
            'form'=>$form->createView(),
            'categories' => $listCategorie
        ]);


    }

    /**
     * @Route("/admin/edit-category/{id}", name="admin_edit_category", methods={"GET", "POST"})
     * @param ObjectManager $em
     * @param Request $request
     * @param Categories $categories
     * @param Categories $categories
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit_category(ObjectManager $em, Request $request, Categories $categories, random $random) {

        $editCtaegorie = $this->createForm("App\Form\CategorieFormType", $categories);
        $editCtaegorie->handleRequest($request);

        if($editCtaegorie->isSubmitted() && $editCtaegorie->isValid()) {

            if($categories->getPhoto()!=null){
                $photo = $categories->getPhoto();

                $fileName = $this->slugify($random->random()).'.'.$photo->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $photo->move(
                        $this->getParameter('categorie_assets_dir'),
                        $fileName
                    );
                } catch (FileException $e) {
                    die("Une erreur s'est produite lors du téléchargement de l'image sur le serveur !");
                }

                $categories->setPhoto($fileName);
            } else{
                $categories->setPhoto($request->get('temporary'));
            }
            //Sauvegarde en BDD
            $em=$this->getDoctrine()->getManager();
            $em->persist($categories);
            $em->flush();

            // Notification
            $this->addFlash('success','La catégorie a bien été mis à jour !');
            return $this->redirectToRoute('admin_category');
        }

        return $this->render("admin/pages/edit-category.html.twig", [
            'form' => $editCtaegorie->createView(),
            'categorie' => $categories
        ]);

    }

    /**
     * @Route("/admin/delete-category/{id}", name="admin_delete_category", methods="DELETE")
     * @param $id
     * @param Categories $categories
     * @param Request $request
     * @param ObjectManager $em
     * @param LivresRepository $livresRepository
     * @param CommentRepository $commentRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete_category($id, Categories $categories, Request $request, ObjectManager $em, LivresRepository $livresRepository, CommentRepository $commentRepository) {

        $deleteBooks = $livresRepository->findBy(['categorie' => $id]);

        $deleteComment = $commentRepository->findBy(['livre' => $deleteBooks]);

        if($this->isCsrfTokenValid('delete' . $categories->getId(), $request->get("_token"))) {
            if($deleteComment) {
                foreach ($deleteComment as $commentd) {
                    $em->remove($commentd);
                }
            }
            if($deleteBooks) {
                foreach ($deleteBooks as $booksd) {
                    $em->remove($booksd);
                }
            }
            $em->remove($categories);
            $em->flush();
            $this->addFlash("success", "La catégorie ".$categories->getCategorie()." à bien ete supprimer");
        }

        return $this->redirectToRoute("admin_category");

    }

}
