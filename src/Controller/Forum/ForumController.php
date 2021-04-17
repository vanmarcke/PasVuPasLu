<?php
namespace App\Controller\Forum;

use App\Entity\Forum;
use App\Entity\ForumReply;
use App\Repository\ForumReplyRepository;
use App\Repository\ForumRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ForumController extends AbstractController {

    /**
     * @var ForumRepository
     */
    private $forumRepository;

    /**
     * @var forumReplyRepository
     */
    private $forumReplyRepository;

    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * ForumController constructor.
     * @param ForumRepository $forumRepository
     * @param ObjectManager $em
     * @param ForumReplyRepository $forumReplyRepository
     */
    public function __construct(ForumRepository $forumRepository, ObjectManager $em, ForumReplyRepository $forumReplyRepository) {

        $this->forumRepository = $forumRepository;

        $this->forumReplyRepository = $forumReplyRepository;

        $this->em = $em;

    }


    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("forum/creer", name="forum_addtopic")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addNewTopic(Request $request) {

        $forum = new Forum();

        $form = $this->createForm("App\Form\ForumType", $forum);
        // Gerer la requête http
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $forum->setForums($this->getUser())->getId();
            $this->em->persist($forum);
            $this->em->flush();
            return $this->redirectToRoute("forum_topic", [
                'slug' => $forum->getSlug(),
                'id' => $forum->getId()
            ]);
        }

        return $this->render("forum/add_topic.html.twig", [
            'forum' => $forum,
            'form' => $form->createView()
        ]);

    }


    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("forum/sujet/{slug}-{id}", name="forum_topic", requirements={"slug": "[a-zA-Z0-9\-]*"})
     * @param $slug
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function forum($slug, $id, Request $request) {

        $topic = $this->forumRepository->find($id);

        $messages = $this->forumReplyRepository->findAllby($id);

        $reply = new ForumReply();

        $form = $this->createForm("App\Form\ForumReplyType", $reply);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $reply->setForum($this->forumRepository->find($id));
            $reply->setUserPost($this->getUser())->getId();
            $this->em->persist($reply);
            $this->em->flush();
            $this->addFlash('success', 'Votre message à bien été ajouté dans la discussion');
            return $this->redirectToRoute("forum_topic", [
                'slug' => $topic->getSlug(),
                'id' => $topic->getId(),
                '_fragment' => 'success'
            ]);
        }

        if($topic->getSlug() !== $slug) {
            return $this->redirectToRoute("forum_addtopic", [
                'slug' => $topic->getSlug(),
                'id' => $topic->getId()
            ], 301);
        }

        return $this->render('forum/topic.html.twig', [
            'topic' => $topic,
            'user' => $topic->getForums(),
            'form' => $form->createView(),
            'messages' => $messages
        ]);

    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("forum/message/edit/{id}", name="forum_edit_reply", requirements={"id": "[0-9\-]*"}, methods="GET|POST")
     * @param ForumReply $reply
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function forumReplyEdit(ForumReply $reply, Request $request) {

        if($reply->getUserPost()->getId() != $this->getUser()->getId()) {
            return $this->redirectToRoute("forum_topic", [
               'slug' => $reply->getForum()->getSlug(),
               'id' => $reply->getForum()->getId()
            ]);
        }

        $formReply = $this->createForm("App\Form\ForumReplyType", $reply);

        $formReply->handleRequest($request);

        if($formReply->isSubmitted() && $formReply->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Votre message a bien été modifié');
            return $this->redirectToRoute("forum_topic", [
                'slug' => $reply->getForum()->getSlug(),
                'id' => $reply->getForum()->getId(),
                '_fragment' => 'success'
            ]);
        }

        return $this->render("forum/reply_edit.html.twig", [
            'form' => $formReply->createView()
        ]);

    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("forum/sujet/edit/{id}", name="forum_edit_topic", requirements={"id": "[0-9\-]*"}, methods="GET|POST")
     * @param Forum $forum
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function forumTopicEdit(Forum $forum, Request $request) {

        if($forum->getForums()->getId() != $this->getUser()->getId()) {
            return $this->redirectToRoute("forum_topic", [
                'slug' => $forum->getSlug(),
                'id' => $forum->getId()
            ]);
        }

        $forumTopic = $this->createForm("App\Form\ForumType", $forum);

        $forumTopic->handleRequest($request);

        if($forumTopic->isSubmitted() && $forumTopic->isValid()) {
            $this->em->flush();
            return $this->redirectToRoute("forum_topic", [
                'slug' => $forum->getSlug(),
                'id' => $forum->getId()
            ]);
        }

        return $this->render("forum/edit_topic.html.twig", [
            'form' => $forumTopic->createView()
        ]);

    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("forums", name="forums")
     */
    public function forums(ForumRepository $forumRepository) {      

        return $this->render("forum/forum.html.twig", [
            'forums' => $forumRepository->findBy([], ['created_at' => 'DESC']),
        ]);
    }


    /**
     * @Route("/admin/delete/forum/{id}", name="delete_forum", methods="DELETE")
     * @param Forum $forum
     * @param ForumReplyRepository $forumReply
     * @param Request $request
     * @param ObjectManager $em
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteForum(Forum $forum, ForumReplyRepository $forumReply, Request $request, ObjectManager $em) {

       $deleteReply = $forumReply->findBy(['Forum' => $forum]);
       

        if($this->isCsrfTokenValid('delete' . $forum->getId(), $request->get("_token"))) {
          $deleteReply = $forumReply->findBy(['Forum' => $forum]);

            if($deleteReply) {
               foreach ($deleteReply as $reply) {
                     $em->remove($reply);
                }
             }
            
            $em->remove($forum);
            $em->flush();
            $this->addFlash("success", "Le forum a bien été supprimé");
        }

        return $this->redirectToRoute("forums");

    }

}