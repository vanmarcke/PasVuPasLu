<?php
namespace App\Controller\Messinger;

use App\Entity\PrivateMessage;
use App\Form\PrivateMessageType;
use App\Repository\PrivateMessageRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface as EntityManager;

class MessingerController extends AbstractController {

    private $privateMessageRepository;

    private $em;

    /**
     * MessingerController constructor.
     * @param PrivateMessageRepository $privateMessageRepository
     * @param ObjectManager $em
     */
    public function __construct(PrivateMessageRepository $privateMessageRepository, ObjectManager $em)
    {
        $this->privateMessageRepository = $privateMessageRepository;
        $this->em = $em;

    }


    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("inbox", name="messginer_inbox")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function messginger() {

        $messagePriavate = $this->privateMessageRepository->getAllByRecipient($this->getUser());

        return $this->render("messinger/inbox.html.twig", [
            'messages' => $messagePriavate
        ]);

    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("inbox/message/{id}", name="messginer_show")
     * @param \Swift_Mailer $mailer
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function ShowMessage(\Swift_Mailer $mailer, $id, Request $request) {

        $ids = $this->privateMessageRepository->find($id);

        if($ids AND $ids->getUserSender() === $this->getUser() OR $ids->getUserReceiver() === $this->getUser()) {

            $Replymessage = new PrivateMessage();

            $form = $this->createForm(PrivateMessageType::class, $Replymessage)
                ->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()) {
                $Replymessage->setUserSender($this->getUser());
                if($ids->getUserReceiver() === $this->getUser()) {
                    $Replymessage->setUserReceiver($ids->getUserSender());
                } else {
                    $Replymessage->setUserReceiver($ids->getUserReceiver());
                }
                $Replymessage->setIsView(true);
                $this->em->persist($Replymessage);
                $this->em->flush();

                $url = $this->generateUrl('messginer_show', ['id' => $id], UrlGeneratorInterface::ABSOLUTE_URL);

                $sendMessage = (new \Swift_Message('Vous avez reçu un nouveau message privé - PasvuPaslu.com'))
                    ->setFrom('no-replay@pasvupaslu.com')
                    ->setTo($Replymessage->getUserReceiver()->getEmail())
                    ->setBody($this->render("mail/sendMail.html.twig", [
                        'sid' => $url
                    ]),'text/html'
                    );

                $mailer->send($sendMessage);

                $this->addFlash('success', 'Votre message a bien été envoyé');
                return $this->redirectToRoute('messginer_show', [
                    'id' => $id
                ]);
            }

            $messagePriavate = $this->privateMessageRepository->getAllByRecipient($this->getUser());

            $message = $this->privateMessageRepository->getById($ids->getUserReceiver(), $ids->getUserSender());

            if($ids->getUserSender() === $this->getUser()) {
                $user = $ids->getUserReceiver();
            } else {
                $user = $ids->getUserSender();
            }

            return $this->render("messinger/message.html.twig", [
                'messages' => $messagePriavate,
                'msg' => $message,
                'recever' => $user,
                'form' => $form->createView()
            ]);

        } else {

            return $this->redirectToRoute('messginer_inbox');

        }

    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("inbox/new/{id}", name="messginer_send")
     * @param \Swift_Mailer $mailer
     * @param $id
     * @param UserRepository $userRepository
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sendMessages(\Swift_Mailer $mailer, $id, UserRepository $userRepository, Request $request) {

        $userReceiver = $userRepository->find($id);

        if($userReceiver and $userReceiver->getId() == $id && $this->getUser()->getId() != $id) {

           $message = new PrivateMessage();

           $form = $this->createForm(PrivateMessageType::class, $message);

           $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()) {
                $message->setUserSender($this->getUser());
                $message->setUserReceiver($userReceiver);
                $message->setIsHead(true);
                $message->setIsView(true);
                $this->em->persist($message);
                $this->em->flush();

                $sendMessage = (new \Swift_Message('Vous avez reçu un nouveau message privé - PasvuPaslu.com'))
                    ->setFrom('no-replay@pasvupaslu.com')
                    ->setTo($message->getUserReceiver()->getEmail())
                    ->setBody($this->render("mail/sendMail.html.twig", [
                        'sid' => $this->generateUrl('messginer_show', ['id' => $id], UrlGeneratorInterface::ABSOLUTE_URL)
                    ]),'text/html'
                    );

                $mailer->send($sendMessage);

                $this->addFlash('success', 'Votre message a bien été envoyé');

                return $this->redirectToRoute('messginer_show', [
                    'id' => $message->getId()
                ]);
            }

            return $this->render("messinger/sendMessages.html.twig", [
                'form' => $form->createView(),
                'userInbox' => $userReceiver
            ]);

        } else {

            return $this->redirectToRoute('messginer_inbox');

        }

    }
    

    /**
     * @Route("inbox/delete/message/{id}", name="inbox_delete_message", methods={"DELETE"})
     * @param Request $request
     * @param ObjectManager $em
     * @param PrivateMessage $PrivateMessage
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete_message(Request $request, ObjectManager $em, PrivateMessage $PrivateMessage) {

        if($this->isCsrfTokenValid('delete' . $PrivateMessage->getId(), $request->get("_token"))) {
            $em->remove($PrivateMessage);
            $em->flush();            
        }
        
        return $this->redirectToRoute("messginer_inbox");
    }  
}


