<?php
namespace App\Controller;

use App\Repository\ContentRepository;

    use App\Entity\Categories;      
    use App\Entity\User;
    use App\Entity\Contact;
    use App\Entity\Edito;
    use App\Entity\ArticlesMag;
    use App\Repository\EditoRepository;
    use App\Repository\ForumRepository;
    use App\Repository\UserRepository;
    use App\Repository\DasboardRepository;
    use App\Repository\ArticlesMagRepository;
    use App\Repository\MagContentsRepository;
    use App\Repository\ProfilUserRepository;  
    use App\Repository\CommentRepository;   
    use App\Repository\QuoteRepository;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Routing\Annotation\Route;
    use App\Form\ContactTerhomaFormType;
    use App\Repository\BandeauRepository;

    class HomeController extends AbstractController {

        /**
         * @Route("/", name="home")
         * @param QuoteRepository $quoteRepository
         * @param UserRepository $repository
         * @param ProfilUserRepository $profilRepository
         * @param ForumRepository $forumRepository
         * @param CommentRepository $commentRepository
         * @return \Symfony\Component\HttpFoundation\Response
         */
    public function index(
        DasboardRepository $dasboardRepository, 
        QuoteRepository $quoteRepository, 
        UserRepository $repository, 
        ForumRepository $forumRepository, 
        ContentRepository $contentRepository,
        CommentRepository $commentRepository,
        ProfilUserRepository $profilRepository
        ){
        
        $bestLectorCommentator = $commentRepository->find9MoreActiveLectorCommentator();
        foreach ($bestLectorCommentator as $test) {
            $ids[] = $profilRepository->findBy(['user' => $test['id']]);
        }
        $premierslivre = $commentRepository->firstsCoverRate(); 
        
         
        return $this->render("pages/index.html.twig", [
            'current_menu' => 'home',
            'ids' => $ids,
            'quote' => $quoteRepository->find(1),            
            'editable' => $dasboardRepository->find(1),
            'users' => $repository->findLatestAuteurs('ROLE_LECTEUR', 24),
            'forums' => $forumRepository->findBy([], ['created_at' => 'DESC'], 4),
            'Auteur_titre' => $contentRepository->findByCle('Auteur_titre'),
            'Paragraphe'=> $contentRepository->findByCle('Par1'),
            'bloc2_titre'=> $contentRepository->findByCle('bloc2_titre'),
            'bloc1_start' => $contentRepository->findByCle('bloc1_start'),
            'bloc1_end' => $contentRepository->findByCle('bloc1_end'), 
            'bloc3' => $contentRepository->findByCle('bloc3'),
            'carroussel'=> $contentRepository->findByCle('carroussel'),
            'carroussel_1'=> $contentRepository->findByCle('carroussel_1'),
            'pub1' => $contentRepository->findByCle('pub1'),
            'pub2' => $contentRepository->findByCle('pub2'),
            'pub1_img' => $contentRepository->findByCle('pub1_img'),
            'pub2_img' => $contentRepository->findByCle('pub2_img'),
            'pub1_video' => $contentRepository->findByCle('pub1_video'),
            'pub2_video' => $contentRepository->findByCle('pub2_video'),
            'carroussel_bas' => $contentRepository->findByCle('carroussel_bas'),
            'premierslivre' => $commentRepository->firstsCoverRate(),
        ]);

    }  
    
    /**
     * @Route("magazine", name="magazine")
     * @param ArticlesMag $ArticlesMag
     */
    public function magazine(ContentRepository $ContentRepository, ArticlesMagRepository $ArticlesMagRepository, MagContentsRepository $MagContentsRepository, ContentRepository $contentRepository) {

        
        $bloc_sommaire = $MagContentsRepository->findByCle('Sommaire');
        $bloc_sommaire1 = $MagContentsRepository->findByCle('Sommaire1');
        $bloc_edition = $MagContentsRepository->findByCle('Edition');
        $bloc_dosspec = $MagContentsRepository->findByCle('DosSpec');
        $bloc_titre = $MagContentsRepository->findByCle('TitreB1_2');
        $bloc_gdtitre = $MagContentsRepository->findByCle('GrandTitre');

        $listeImage = $MagContentsRepository->findByCle('GrandeBan');
        $imageB1 = $MagContentsRepository->findByCle('ImageB1_2');
        $imageB2 = $MagContentsRepository->findByCle('ImageB1_3');
        $imageB3 = $MagContentsRepository->findByCle('ImageB2_1');

        $carroussel = $contentRepository->findByCle('bandeau_magazine');
        $carroussel_first = $contentRepository->findByCle('bandeau_magazine_first');
    

        return $this->render("magazine/index.html.twig", [
            'articles' => $ArticlesMagRepository->findBy([], ['created_at' => 'DESC']),
            'puce' => $bloc_sommaire,
            'puce1' => $bloc_sommaire1,
            'magText' => $bloc_edition,
            'magTextspec' => $bloc_dosspec,
            'magTexttitre' => $bloc_titre,
            'magTextgdtitre' => $bloc_gdtitre,
            'magContents' => $listeImage,
            'magContents1' => $imageB1,
            'magContents2' => $imageB2,
            'magContents3' => $imageB3, 
            'carroussel' => $carroussel,
            'carroussel_first' => $carroussel_first[0]  
        ]);

    }   
    

	 /**
     * @Route("auteurs", name="authors")
     */
    public function authors() {
       
        return $this->render('auteur/auteurs.html.twig', [
        'current_menu' => 'authors',
    ]);
}

    /**
     * @Route("/categories/{categorie}", name="category", methods={"GET"})
     * @param Categories|null $categories
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function categories( Categories $categories=null) {


        return $this->render("pages/category.html.twig", [
            'categories' => $categories,
            'livres' => $categories->getLivre(),
            'categorie'=>$categories->getCategorie(),
            'categoriephoto' => $categories->getPhoto()
        ]);

    }                

        /**
         * @Route("Informations-legales", name="legal")
         * @param BandeauRepository $BandeauR
         */
        public function legal(ContentRepository $contentRepository, BandeauRepository $BandeauR) {

            $info_legal = $contentRepository->findByCle('info_legal');
            $bandeau = $BandeauR->findByCle('page_legal');
            $carroussel = $contentRepository->findByCle('bandeau_info_legal');
            $carroussel_first = $contentRepository->findByCle('bandeau_info_legal_first');
            
            return $this->render("pages/legal.html.twig", [
                'info_legal'=> $info_legal,
                'bandeau' => $bandeau[0],
                'carroussel' => $carroussel, 
                'carroussel_first' => $carroussel_first[0]
                ]);

        }


        /**
         * @Route("edito", name="edito")
         * @param EditoRepository $repository
         * @param BandeauRepository $BandeauR
         * @return \Symfony\Component\HttpFoundation\Response
         */
        public function edito(EditoRepository $repository, BandeauRepository $BandeauR, ContentRepository $contentRepository) {

            $bandeau = $BandeauR->findByCle('page_edito');
            $carroussel = $contentRepository->findByCle('bandeau_edito');
            $carroussel_first = $contentRepository->findByCle('bandeau_edito_first');

            return $this->render("pages/edito.html.twig", [
                'current_menu' => 'edito',
                'editos' => $repository->findBy([], ['created_at' => 'DESC']),
                'bandeau' => $bandeau[0],
                'carroussel' => $carroussel,
                'carroussel_first' => $carroussel_first[0]
            ]);

        }


        /**
         * @Route("edito/{slug}/{id}", name="edito_view", methods={"GET"}, requirements={"slug": "[a-z0-9\-]*"})
         * @param Edito $edito
         * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
         */
        public function edito_view(Edito $edito){

            return $this->render("pages/edito-view.html.twig", [
                'current_menu' => 'edito',
                'edito' => $edito,
            ]);

        }

        /**
         * @Route("contact", name="contact")
         * @param \Swift_Mailer $mailer
         * @param Request $request
         * @param BandeauRepository $BandeauR
         * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
         */
        public function contact(\Swift_Mailer $mailer, Request $request, BandeauRepository $BandeauR, ContentRepository $contentRepository) {

            $contact = new Contact();

            $bandeau = $BandeauR->findByCle('page_contact');

            $carroussel = $contentRepository->findByCle('bandeau_contact');
            $carroussel_first = $contentRepository->findByCle('bandeau_contact_first');

            $form = $this->createForm("App\Form\ContactType", $contact);



            // Gerer la requête http
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()) {

                $sendMessage = (new \Swift_Message('Vous avez un nouveau message via le site PasvuPaslu.com'))
                    ->setFrom('no-reply@terhoma.org')
                    ->setTo('stephane.theri@terhoma.org')
                    ->setBody($this->render("mail/contact.html.twig", [
                        'contact' => $contact
                    ]),'text/html');

                $mailer->send($sendMessage);

                $this->addFlash('success', 'Merci ! Votre message a bien été envoyé. Une réponse vous sera apportée dans les plus brefs délais');
                return $this->redirectToRoute('contact');
            }


            return $this->render("pages/contact.html.twig", [
                'current_menu' => 'contact',
                'bandeau' => $bandeau[0],
                'form' => $form->createView(), 
                'carroussel' => $carroussel, 
                'carroussel_first' => $carroussel_first[0]
            ]);

        }


    /**
     * @Route("/service_publicite", name="contact_terhoma")
     * @param BandeauRepository $BandeauR
     */
    public function sendAtTerhoma(Request $req, \Swift_Mailer $mailer, BandeauRepository $BandeauR, ContentRepository $contentRepository)
    {
        $bandeau = $BandeauR->findByCle('page_pub');

        $carroussel = $contentRepository->findByCle('bandeau_service_pub');
        $carroussel_first = $contentRepository->findByCle('bandeau_service_pub_first');

        $form = $this->createForm(ContactTerhomaFormType::class);
        $form->HandleRequest($req);

        if($form->isSubmitted() && $form->isValid())
        {
            //On recupère les données de contact
           $contactTerhoma = $form->getData();

           //Ici nous enverrons le mail
           
           $message = (new \Swift_Message('Nouveau message : service publicité'))
                //on attribut l'expéditeur
                ->setFrom('no-reply@terhoma.org')
                
                //on attribut les destinataire
                ->setTo('contact@terhoma.org')

                //on crée le message avec la vue twig
                ->setBody(
                    $this->renderView(
                        'mail/contactTerhoma.html.twig', compact('contactTerhoma')
                    ),
                    'text/html'
                )
            ;
            
            // on envoie le message
            $mailer->send($message);

            $this->addFlash('success', 'Merci ! Votre message a bien été envoyé. Une réponse vous sera apportée dans les plus brefs délais');
            return $this->redirectToRoute('contact_terhoma');
        }

        return $this->render('pages/contactServicePub.html.twig', [
            'form' => $form->createView(),
            'bandeau' => $bandeau[0],
            'carroussel' => $carroussel, 
            'carroussel_first' => $carroussel_first[0]
        ]);
    }

}
