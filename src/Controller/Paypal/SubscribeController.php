<?php

namespace App\Controller\Paypal;


use App\Entity\User;
use App\Repository\CouponRepository;
use App\Entity\AllCoupons;
use const App\Services\Paypal\password_paypal;
use App\Services\Paypal\PaypalSubscription;
use const App\Services\Paypal\signature_paypal;
use const App\Services\Paypal\username_paypal;
use Doctrine\Common\Persistence\ObjectManager;
use Swift_Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class SubscribeController extends AbstractController
{

    /**
     * @Route("newPay/{slug}", name="newPay")
     * @return Response
     */
    public function newPayPaypal($slug) {

        $paypalSubscription = new PaypalSubscription(username_paypal, password_paypal, signature_paypal,
            PaypalSubscription::getOffers());

        if($slug === 'auteur') {

            return new Response(json_encode($paypalSubscription->subscribe('inscription auteur')));

        }// }elseif ($slug === 'lecteur') {

        //     return new Response(json_encode($paypalSubscription->subscribe('inscription lecteur')));

        // }

        return new Response(json_encode('No offer was found for this request'));

    }

    /**
     * @Route("process", name="process")
     * @param Swift_Mailer $mailer
     * @param Request $request
     * @param ObjectManager $em
     * @param Session $session
     * @return Response
     * @throws \Exception
     */
    public function process(Swift_Mailer $mailer, Request $request, ObjectManager $em, Session $session) {

        $paypal = new PaypalSubscription(username_paypal, password_paypal, signature_paypal,
            PaypalSubscription::getOffers());
        $payer = $paypal->IsValidsubscribe($request->get('token'));

        if($request->get('payerID') === $payer['payerid']) {

            $user = new User();

            $em=$this->getDoctrine()->getManager();
            $user = $session->get('register');
            $user->setPayerId($payer['payerid']);
            $user->setPaypalProfilId($payer['profileid']);
            $user->setPayerStatus('verified');
            $user->setSubscriptionEnd(new \DateTime('+1 day'));
            $em->persist($user);
            $em->flush();

            $message = (new \Swift_Message('Bienvenue sur PasvuPasLu'))
                ->setFrom('no-reply@pasvupaslu.com')
                ->setTo($user->getEmail())
                ->setBody($this->render("mail/welcolme.html.twig", [

                ]),'text/html'
                );

            $mailer->send($message);

            $this->addFlash('success',
                'Félicitations votre compte a été créé avec succès vous pouvez maintenant vous connecter avec vous identifiants');

            $session->remove('register');

            return new Response(json_encode($this->generateUrl('register_user_profil')));

        }

        return new Response(json_encode($this->generateUrl('home')));

    }

    /**
     * @Route("ipn", name="ipn")
     * @param Request $request
     * @param ObjectManager $em
     * @return Response
     * @throws \Exception
     */
    public function ipn(Request $request, ObjectManager $em) {

        $data_post = json_encode($_POST);

        $data = json_decode($data_post);

        $paypalIPN = new PaypalSubscription(username_paypal, password_paypal, signature_paypal,
        PaypalSubscription::getOffers());

        if($paypalIPN->CheckIPN($data)) {

            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['payer_id' => $data->payer_id]);

            $payer_id = $paypalIPN->getProfilDetail($user->getPaypalProfilId());

            dump($payer_id);

            $user->setSubscriptionEnd(new \DateTime($payer_id['PROFILESTARTDATE']));

            $user->setPayerStatus($payer_id['STATUS']);

            $em->persist($user);
            $em->flush();

            // return $this->redirectToRoute('home');

            // dump($data, $payer_id);
            return new Response(json_encode('The query has been executed successfully'));

        }

        return new Response(json_encode('No exceptions were issued'));
        // return $this->redirectToRoute('home');

    }
    
    
    /**
     * @Security("!is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/coupon-verefi", name="coupon_verefi", methods={"POST"})
     * @param Swift_Mailer $mailer
     * @param CouponRepository $couponRepository
     * @param Request $request
     * @param Session $session
     * @return Response
     * @throws \Exception
     */
    public function coupon_verefi(\Swift_Mailer $mailer, CouponRepository $couponRepository, Request $request, Session $session) {

        if(!$session->get('register')) {
            return new Response(json_encode(['status' => 'errors', 'request' => 'Aucun profil n\'a été sauvegardé remplissez à nouveau le formulaire']));
        }
        

       if($this->isCsrfTokenValid('promo', $request->get("_token"))) {

           $coupon = $couponRepository->findOneBy(['promo' => $request->get('_promo')]);

           $expire = $coupon->getExpirdateAt() >= new \DateTime();

            if($coupon && $expire) {

                $user = new User();
                $allcoupons = new AllCoupons();
                
                $coupon->setIsValid(1);

                $emu=$this->getDoctrine()->getManager();
                $user = $session->get('register');
                $user->setSubscriptionEnd(new \DateTime('+365 day'));
                $emu->persist($user);
                $emu->flush();
                
                $em=$this->getDoctrine()->getManager();
                $allcoupons->setUserId($user->getId());
                $allcoupons->setCouponId($coupon->getId());
                $em->persist($allcoupons);
                $em->persist($coupon);
                $em->flush();

                $message = (new \Swift_Message('Bienvenue sur PasvuPasLu'))
                    ->setFrom('no-replay@pasvupaslu.com')
                    ->setTo($user->getEmail())
                    ->setBody($this->render("mail/welcolme.html.twig", [

                    ]),'text/html'
                    );

                $mailer->send($message);

                $this->addFlash('success',
                    'Félicitations votre compte a été créé avec succès vous pouvez maintenant vous connecter avec vos identifiants');

                $session->remove('register');
                
                return new Response(json_encode(['status' => 'success', 'redirect' => $this->generateUrl('register_user_profil')]));

            }


            return new Response(json_encode(['status' => 'errors', 'request' => 'Le code coupon que vous avez saisi n\'est pas valide où expiré']));

      }

        return new Response(json_encode(['status'=>'errors', 'message' => "Le token de sécurité n'est pas valide"]));

    }

}
