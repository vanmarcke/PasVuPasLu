<?php

namespace App\Controller;


use Swift_Mailer;
use App\Entity\User;
use App\Services\random;
use App\Entity\ProfilUser;
use App\Form\UserFormType;
use App\Form\ReaderFormType;
use App\Form\EditPasswordType;
use App\Form\ProfilUserFormType;
use App\Repository\LivresRepository;
use App\Repository\BandeauRepository;
use App\Repository\CommentRepository;
use App\Repository\ContentRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\EntityManagerInterface as EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class MembersController extends AbstractController

{
    use HelperTrait;


    /**
     * @Security("!is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/registration/user_auteur", name="register_user")
     * @param Request $request
     * @param User|null $user
     * @param BandeauRepository $BandeauR
     * @param UserPasswordEncoderInterface $encoder
     * @param Session $session
     * @return Response
     */
    public function inscrirUser(Request $request, User $user=null, UserPasswordEncoderInterface $encoder, Session $session, BandeauRepository $BandeauR, ContentRepository $contentRepository )
    {
        $bandeau = $BandeauR->findByCle('page_registration_auteur');
        $carroussel = $contentRepository->findByCle('bandeau_inscription_auteur');
        $carroussel_first = $contentRepository->findByCle('bandeau_inscription_auteur_first');
        if (!$user){
            $user = new User();
            $user->setRoles(['ROLE_AUTEUR']);
        }

        $form = $this->createForm(UserFormType::class, $user)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $pasword = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($pasword);

            $session->set('register', $user);

            return new Response(json_encode(['status'=>'success']));

        } else {

            $errors = $form->getErrors(true);

            foreach ($errors as $error) {
                return new Response(json_encode(['request' => $error->getMessage(), 'status' => 'errors']));
            }

        }

        return $this->render('pages/registration-author.html.twig', [
            'form'=>$form->createView(),
            'bandeau' => $bandeau[0],
            'carroussel' => $carroussel, 
            'carroussel_first' => $carroussel_first[0]
        ]);


    }

    /**
     * @Security("!is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/registration/user_lecteur", name="register_user1")
     * @param Request $request
     * @param User|null $user
     * @param BandeauRepository $BandeauR
     * @param UserPasswordEncoderInterface $encoder
     * @param Session $session
     */
    public function inscrirUser1(Request $request, User $user=null, UserPasswordEncoderInterface $encoder, Session $session, EntityManager $em, Random $random, BandeauRepository $BandeauR, ContentRepository $contentRepository)
    {

        $user = new User();
        $profilUser = new ProfilUser;
        $bandeau = $BandeauR->findByCle('page_registration_lecteur');
        $carroussel = $contentRepository->findByCle('bandeau_inscription_lecteur');
        $carroussel_first = $contentRepository->findByCle('bandeau_inscription_lecteur_first');
        $form = $this->createForm(ReaderFormType::class, $user)
            ->handleRequest($request);
      

        if ($form->isSubmitted() && $form->isValid()){
      
            $user->setRoles(['ROLE_LECTEUR']);
            $profilUser ->setPseudo($form->get('pseudo')->getData());
            $profilUser->setUser($user);
            
            $profilUser ->setVille($form->get('ville')->getData());
            $profilUser ->setAdress($form->get('adress')->getData());
            $profilUser ->setCountry($form->get('country')->getData());
            $profilUser ->setCodePostale($form->get('codePostale')->getData());
            $profilUser ->setTelephone($form->get('telephone')->getData());
            $profilUser ->setBirthDate($form->get('birthDate')->getData());

            /** @var UploadedFile $image*/
            $photo = $form['photo']->getData();
            if($photo){
                $fileName = $this->slugify($random->random(10)).'.'.$photo->guessExtension();
                    // Move the file to the directory where brochures are stored
                    try {
                        $photo->move($this->getParameter('auter_assets_dir'), $fileName);
                    } catch (FileException $e) {
                        die("Une erreur s'est produite lors du téléchargement de l'image sur le serveur veuillez réessayer ultérieurement.");
                    }
                    $profilUser->setPhoto($fileName);
            } else {
                    $profilUser->setPhoto('d.png');
                }
            
            
            $pasword = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($pasword);
             
            $em->persist($user);
            $em->persist($profilUser);
            $em->flush();
            $this->addFlash("success", "Vous avez bien été enregistré - Connectez vous !");
            return $this->redirectToRoute("security_login");

        } 
        //Affichage dans la vue
        return $this->render('pages/registration-reader.html.twig', [
            'form'=>$form->createView(),
            'bandeau' => $bandeau[0],
            'carroussel' => $carroussel, 
            'carroussel_first' => $carroussel_first[0]
        ]);


    }
  



    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/registration/user/profil", name="register_user_profil")
     * @param Request $request
     * @param ProfilUser|null $auterEntity
     * @param LivresRepository $livresRepository
     * @param CommentRepository $commentRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function inscrirUserProfil(Request $request, ProfilUser $auterEntity = null, LivresRepository $livresRepository, CommentRepository $commentRepository, Random $random)
    {

        if (!$auterEntity){
            $auterEntity = new ProfilUser();
            $auterEntity->setUser($this->getUser());
        }



//        Création du Formulaire
        $form = $this->createForm(ProfilUserFormType::class, $auterEntity)
            ->handleRequest($request);

//         Si le formulaire est soumis et valide
        if ($form->isSubmitted()&& $form->isValid()){
            /** @var UploadedFile $photo*/
            if($auterEntity->getPhoto()!=null){
                    $photo = $auterEntity->getPhoto();

                    $fileName = $this->slugify($random->random(10)).'.'.$photo->guessExtension();

                    // Move the file to the directory where brochures are stored
                    try {
                        $photo->move(
                            $this->getParameter('auter_assets_dir'),
                            $fileName
                        );
                    } catch (FileException $e) {
                        die("Une erreur s'est produite lors du téléchargement de l'image sur le serveur veuillez réessayer ultérieurement.");
                    }

                    $auterEntity->setPhoto($fileName);
                } else {
                    $auterEntity->setPhoto('d.png');
                }


            //Sauvegarde en BDD
            $em=$this->getDoctrine()->getManager();
            $em->persist($auterEntity);
            $em->flush();

//            //Rediraction
            return $this->redirectToRoute('register_user_profil');
        }

        $livres = $livresRepository->findBy(['user' => $this->getUser()]);

        $comments = $commentRepository->findBy(['user' => $this->getUser()]);

//    Affichage dans la vue
        return $this->render('members/profil.html.twig', [
            'form'=>$form->createView(),
            'livres' => $livres,
            'comments' => $comments
        ]);


    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("settings/{id}", name="settings", methods={"GET", "POST"})
     * @param ProfilUser $profilUser
     * @param Request $request
     * @param ObjectManager $em
     * @param random $random
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function settings(ProfilUser $profilUser, Request $request, ObjectManager $em, random $random) {


        if($profilUser->getUser() === $this->getUser()) {

            $form = $this->createForm(ProfilUserFormType::class, $profilUser)
                ->handleRequest($request);

            if ($form->isSubmitted()&& $form->isValid()){

                if($profilUser->getPhoto()!=null){
                    $photo = $profilUser->getPhoto();

                    $fileName = $this->slugify($random->random(10)).'.'.$photo->guessExtension();

                    // Move the file to the directory where brochures are stored
                    try {
                        $photo->move(
                            $this->getParameter('auter_assets_dir'),
                            $fileName
                        );
                    } catch (FileException $e) {
                        die("Une erreur s'est produite lors du téléchargement de l'image sur le serveur veuillez réessayer ultérieurement.");
                    }

                    $profilUser->setPhoto($fileName);
                } else {
                    $profilUser->setPhoto($request->get('temporary'));
                }

                $em=$this->getDoctrine()->getManager();
                $em->persist($profilUser);
                $em->flush();

                $this->addFlash('success','Vos modifications ont bien été effectué');

                return $this->redirectToRoute('settings', [
                    'id' => $profilUser->getId()
                ]);
            }


            return $this->render("pages/settings.html.twig", [
                'form' => $form->createView(),
                'temporary' => $profilUser->getPhoto(),
                'd' => glob('assets/images/profil/d/*')
            ]);

        }

        return $this->redirectToRoute("register_user_profil");

    }
    
    /**
     * @Route("/change/password", name="edit_password")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param $request Request
     * @param UserPasswordEncoderInterface $encoder
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit_password(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $origPwd = $this->getUser()->getPassword();
        $form = $this->createForm(EditPasswordType::class, $this->getUser());
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            /** @var User $user */
            $user = $form->getData();
            $pwd = $user->getPassword() ? $encoder->encodePassword($user, $user->getPassword()) : $origPwd;
            $user->setPassword($pwd);
            $em = $this->getDoctrine()->getManager();
            if ($form->isValid()) {
                $em->persist($user);
                $em->flush();
                $this->addFlash('success', 'Le mot de passe a été changé');
            }
            $em->refresh($user);
        }

        return $this->render('pages/edit_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
    

    /**
     * @Security("!is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("password/forgot", name="password_forgot")
     * @param Swift_Mailer $mailer
     * @param Request $request
     * @param TokenGeneratorInterface $tokenGenerator
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function forgot(\Swift_Mailer $mailer, Request $request, TokenGeneratorInterface $tokenGenerator) {

        # Verifier que la requête et bien envoyer en post
        if($request->isMethod('POST')) {
            # Récuperer l'email qui & été renseigné dans le champ email
            $email = $request->request->get('email');
            $entityManager = $this->getDoctrine()->getManager();
            # Vérifier si l'email saisi et enregistrer dans la base de données
            $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
            # Générer un token
            $token = $tokenGenerator->generateToken();
            if($user) {
                # Si l'adresse mail à été trouver dans la basse de données en lui envoi un mail pour restaurer sont mot de passe
                $user->setResetToken($token);
                $entityManager->flush();
                $url = $this->generateUrl('security_forgotten_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
                $message = (new \Swift_Message('Récupération de votre mot de passe PasvuPaslu.com'))
                    ->setFrom('no-replay@pasvupaslu.com')
                    ->setTo($user->getEmail())
                    ->setBody($this->render("mail/forgot_password.html.twig", ['url' => $url]),
                        'text/html'
                    );
                $mailer->send($message);
                $this->addFlash('success', "Un lien pour réinitialiser votre mot de passe à été envoyer à l'adresse mail ".$user->getEmail());
            } else {
                $this->addFlash('success', "Un lien pour réinitialiser votre mot de passe à été envoyer à l'adresse mail ".$email);
            }

        }
        return $this->render("pages/password-forgot.html.twig");

    }


    /**
     * @Security("!is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/password/forgot/{token}", name="security_forgotten_password", methods={"GET", "POST"})
     * @param Request $request
     * @param string $token
     * @param UserPasswordEncoderInterface $encoder
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function forgottenPassword(Request $request, $token, UserPasswordEncoderInterface $encoder) {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->findOneBy(['resetToken' => $token]);
        if ($user === null) {
            # Le token n'est pas correct en regerige ver la page de connection
            return $this->redirectToRoute('password_forgot');
        }
        if($request->isMethod('POST')) {
            if($request->request->get('password') === $request->request->get('password-confirm')) {
                $user->setResetToken(null);
                $user->setPassword($encoder->encodePassword($user, $request->request->get('password')));
                $entityManager->flush();
                $this->addFlash('success', 'Votre mot de passe à bien été changer vous pouvez maintenant vous connecter');
                return $this->redirectToRoute('security_login');
            } else {
                $this->addFlash('errors', 'Les deux mot de passe saisis ne correspondent pas !');
            }

        }
        # si le token est correct en affiche la vue pour permettre au membre de changer sont mot de passe
        return $this->render('pages/password.html.twig', [
            'token' => $token
        ]);
    }
    
    
    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("edit/avatar", name="edit_avatar", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function edit_avatar(Request $request) {

        if($request->get('avatar_select')) {

            $entityManager = $this->getDoctrine()->getManager();
            $user = $entityManager->getRepository(ProfilUser::class)->findOneBy(['user' => $this->getUser()]);

            $user->setPhoto($request->get('avatar_select'));
            $entityManager->flush();

            return new Response(json_encode(['status' => 'success', 'redirect' => $this->generateUrl('register_user_profil', [
                'id' => $this->getUser()->getId()])]
            ));
        }

        return new Response(json_encode(['status' => 'errors', 'request' => 'Vous n\'avez sélectionné aucun avatar']));

    }
    

}
