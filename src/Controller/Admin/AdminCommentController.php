<?php

namespace App\Controller\Admin;

use App\Controller\HelperTrait;
use App\Entity\Comment;
use App\Repository\CommentRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class AdminCommentController extends AbstractController
{
    use HelperTrait;

    /**
     * @Route("/admin/comments", name="admin_comments")
     * @param CommentRepository $commentRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function comment(CommentRepository $commentRepository) {

        return $this->render("admin/pages/comment.html.twig", [
            'comments' => $commentRepository->findCommentsValide(),
            'rapports' => $commentRepository->findCommentsRapport()
        ]);

    }


    /**
     * @Route("/admin/delete/comment/{id}", name="admin_delete_comment", methods={"DELETE"})
     * @param Comment $comment
     * @param ObjectManager $em
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function delete_comment(Comment $comment, ObjectManager $em, Request $request) {

        if($this->isCsrfTokenValid('delete' . $comment->getId(), $request->get("_token"))) {
            $em->remove($comment);
            $em->flush();
            $this->addFlash("success", "Le commentaire à bien ete supprimer");
        }

        return $this->redirectToRoute("admin_comments");


    }


    /**
     * @Route("/admin/valide/comment/{id}", name="admin_valide_comment", methods={"POST"})
     * @param Comment $comment
     * @param ObjectManager $em
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function valide_comment(Comment $comment, ObjectManager $em, Request $request) {

        if($this->isCsrfTokenValid('valide' . $comment->getId(), $request->get("_token"))) {
            $comment->setRapport(null);
            $em->persist($comment);
            $em->flush();
            $this->addFlash("success", "Le commentaire a bien été validé");
        }

        return $this->redirectToRoute("admin_comments");


    }


}
