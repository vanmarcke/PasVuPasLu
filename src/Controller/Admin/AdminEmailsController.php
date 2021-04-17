<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UserRepository;
use App\Form\EmailsType;

class AdminEmailsController extends AbstractController
{
    /**
     * @Route("/admin/emails", name="admin_emails")
     *  @param UserRepository $repository
     *  @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $req, UserRepository $repository, \Swift_Mailer $mailer)
    {


        $form = $this->createForm(EmailsType::class);
        $form->HandleRequest($req);


        if($form->isSubmitted() && $form->isValid())
        {
            $to = "";
            $to = $form->get("to")->getData();

            $emailsAuteurs = "";
            $emailsAuteurs = $repository->findEmailsAuteurs('ROLE_AUTEUR');

            $emailsLecteurs = "";
            $emailsLecteurs = $repository->findEmailsLecteurs('ROLE_LECTEUR');

            $emailsTous = "";
            $emailsTous = array_merge($emailsAuteurs, $emailsLecteurs); 

           

            switch ($to) {
                case 'auteur':
                    $emailsContact = $emailsAuteurs;
                    break;
                case 'lecteur':
                    $emailsContact = $emailsLecteurs;
                    break;
                case 'tous':
                    $emailsContact = $emailsTous;
                    break;
                }

                $contactsEmails = array_column($emailsContact, 'email');
                // dd($contactsEmails);
                
                // $lesEmails = implode("; ", $contactsEmails);
                // dd($lesEmails);
       


           //On recupère les données du formulaire
           $contact = $form->getData();

           $userEmail = $this->getUser()->getEmail();

           $subject = $form->get("objet")->getData();
           

           //Ici nous enverrons le mail
           $message = (new \Swift_Message($subject))
           
                //on attribut l'expéditeur
                // ->setFrom($userEmail)
                ->setFrom('no-reply@terhoma.org')
                
                //on attribut un destinataire
                ->setTo("contact@terhoma.org")

                //on attribut les destinataire (cachés aux autres destinataires)
                ->setBcc($contactsEmails)

                //on crée le message avec la vue twig
                ->setBody(
                    $this->renderView(
                        'mail/emails.html.twig', compact('contact')
                    ),
                    'text/html'
                )
            ;
            
            // on envoie le message
            $mailer->send($message);

            $this->addFlash('message', 'Votre message a bien été envoyé !');
            return $this->redirectToRoute('admin_emails');
        }

        return $this->render('admin/pages/adminemails.html.twig', [
            'contactForm' => $form->createView()
        ]);
    }
}