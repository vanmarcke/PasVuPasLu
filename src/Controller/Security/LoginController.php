<?php
namespace App\Controller\Security;

use App\Entity\User;
use App\Entity\Membres;
use App\Form\MembreType;
use App\Repository\BandeauRepository;
use App\Repository\ContentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;


class LoginController extends AbstractController {


    /**
     * Connection d'un membre
     * @Security("!is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/identification", name="security_login")
     * @param AuthenticationUtils $authenticationUtils
     * @param BandeauRepository $BandeauR
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(AuthenticationUtils $authenticationUtils,BandeauRepository $BandeauR, ContentRepository $contentRepository)
    {
        $bandeau = $BandeauR->findByCle('page_registration');
        $carroussel = $contentRepository->findByCle('bandeau_inscription');
        $carroussel_first = $contentRepository->findByCle('bandeau_inscription_first');
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('pages/identification.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
            'bandeau'       => $bandeau[0],
            'carroussel' => $carroussel, 
            'carroussel_first' => $carroussel_first[0]
        ));
    }

    /**
     * Déconnexion d'un Membre
     * @Route("/logout", name="security_logout")
     */
    public function logout() { }



}
