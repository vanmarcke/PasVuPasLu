<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Services\random;
use App\Form\NewDateType;
use App\Repository\UserRepository;
use App\Repository\LivresRepository;
use App\Repository\CommentRepository;
use App\Repository\PaymentRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminUsersLecteurController extends AbstractController
{

    /**
     * @Route("/admin/user/editor", name="admin_user_editor")
     * @param UserRepository $repository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userAuteur(UserRepository $repository) {

        return $this->render("admin/pages/user-editor.html.twig", [
            'users' => $repository->findLatestAuteurs('ROLE_AUTEUR'),
            'CountAuteurs' => $repository->countAuteurs()
        ]);

    }

    /**
     * @Route("/admin/user/readers", name="admin_user_lecteur")
     * @param UserRepository $repository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userLecteur(UserRepository $repository) {

        return $this->render("admin/pages/user-readers.html.twig", [
            'users' => $repository->findLatestAuteurs('ROLE_LECTEUR'),
            'CountLecteurs' => $repository->countLecteurs()
        ]);

    }


    /**
     * @Route("/admin/userInfo/{id}", name="admin_edit_user")
     * @param $id
     * @param User $user
     * @param LivresRepository $livresRepository
     * @param CommentRepository $commentRepository
     * @param PaymentRepository $paymentRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function userInfo($id, User $user, LivresRepository $livresRepository, CommentRepository $commentRepository, PaymentRepository $paymentRepository) {

        if($user->getRoles() == ['ACCOUNT_DELETE']) {
            return $this->redirectToRoute('admin_user_editor');
        }

        return $this->render("admin/pages/user-info.html.twig", [
            'user' => $user,
            'livres' => $user->getLivre(),
            'comments' => $commentRepository->findBy(['user' => $id]),
            'buys' => $paymentRepository->findBy(['user' => $user], ['payment_date_at' =>'DESC']),
            'date' => $user->getSubscriptionEnd() > new \DateTime(),
        ]);

    }


    /**
     * @Route("admin/delete/user/{id}", name="admin_delete_user", methods={"DELETE"})
     * @param $id
     * @param User $user
     * @param Request $request
     * @param random $random
     * @param ObjectManager $em
     * @param LivresRepository $livresRepository
     * @param CommentRepository $commentRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete_user($id, User $user, Request $request, random $random, ObjectManager $em, LivresRepository $livresRepository, CommentRepository $commentRepository) {

        $deleteBooks = $livresRepository->findBy(['categorie' => $id]);

        $deleteComment = $commentRepository->findBy(['livre' => $deleteBooks]);

        if($this->isCsrfTokenValid('delete' . $user->getId(), $request->get("_token"))) {
            if($deleteComment) {
                foreach ($deleteComment as $comments) {
                    $em->remove($comments);
                }
            }
            if($deleteBooks) {
                foreach ($deleteBooks as $books) {
                    $em->remove($books);
                }
            }
            $em->persist($user->setRoles(['ACCOUNT_DELETE']));
            $em->persist($user->setPassword('delete_'.$random->random(100)));
            $em->persist($user->setEmail('delete_'.$random->random(100).'@supprimer.com'));
            $em->persist($user->setNom('Utilisateur'));
            $em->persist($user->setPrenom('Supprimer'));
            $em->persist($user->setPaypalProfilId('Supprimer'));
            $em->persist($user->setPayerId('Supprimer'));
            $em->flush();
            $this->addFlash("success", "L'utilisateur ".$user->getNom()." à été supprimer du site");
        }

        if($user->getRoles() == ['ROLE_LECTEUR']) {
            return $this->redirectToRoute("admin_user_lecteur");
        }
        return $this->redirectToRoute("admin_user_editor");

    }
    /**
     * @Route("admin/date/user/{id}", name="admin_date_user")
     * @param $id
     * @param User $user
     * @param Request $request
     * @param random $random
     * @param ObjectManager $em
     * @param LivresRepository $livresRepository
     * @param CommentRepository $commentRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function edit_date($id, UserRepository $user, Request $request, random $random, ObjectManager $em, LivresRepository $livresRepository, CommentRepository $commentRepository) {

        $user = $user->find($id); 

        $formDate = $this->createForm(NewDateType::class, $user)
            ->handleRequest($request);

        if($formDate->isSubmitted() && $formDate->isValid()){

        $em=$this->getDoctrine()->getManager();
        $em->flush();

        $this->addFlash('success','Votre contenu a bien été mis à jour.');
        return $this->redirectToRoute('admin_edit_user', ["id"=>$id]);
        }


        return $this->render("admin/pages/new_date.html.twig", [
            'formDate'=> $formDate->createView(),
            'user'=> $user,
        ]);

    }

}
