<?php

namespace App\Controller\Paypal;

use App\Entity\PaymentPaypal;
use App\Repository\UserRepository;
use App\Entity\AllCoupons;
use App\Services\Paypal\Paypal;
use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Api\PaymentExecution;
use App\Repository\CouponLivreRepository;

//
use http\Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaymentsController extends AbstractController
{

    /**
     * @Security("is_granted('ROLE_AUTEUR', 'ROLE_ADMIN')")
     * @Route("request", name="request", methods={"GET", "POST"})
     * @param Paypal $paypal
     * @return Response
     */
    public function request(Paypal $paypal) {

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $return_url = $this->generateUrl('response', [], UrlGeneratorInterface::ABSOLUTE_URL);
        $cancel_url = $this->generateUrl('ajouter_livre', [], UrlGeneratorInterface::ABSOLUTE_URL);
        $currency = 'EUR';
        $amountPayable = 60.00;
        $invoiceNumber = uniqid();
        $amount = new Amount();
        $amount->setCurrency($currency)
            ->setTotal($amountPayable);
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setDescription('Ajouter un nouveau livre sur le site pasvupaslu.com')
            ->setInvoiceNumber($invoiceNumber);
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($return_url)
            ->setCancelUrl($cancel_url);
        $payment = new Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setTransactions([$transaction])
            ->setRedirectUrls($redirectUrls);
        try {
            $payment->create($paypal->getApiContext());
        } catch (Exception $e) {
            // dump($e);
            return new Response(json_encode('Impossible de créer un lien pour le paiement'));
        }

        return new Response(json_encode($payment->getToken()));

    }


    /**
     * @Security("is_granted('ROLE_AUTEUR', 'ROLE_ADMIN')")
     * @Route("response", name="response", methods={"GET", "POST"})
     * @param Paypal $paypal
     * @param Request $request
     * @param UserRepository $userRepository
     * @return Response
     */
    public function response(Paypal $paypal, Request $request, UserRepository $userRepository) {

        if (!$request->get('paymentID') && !$request->get('payerID')) {

            return new Response(json_encode('PaymentId et PayerID sont manquants dans la réponse.'));

        }

        $paymentId = $request->get('paymentID');
        $payment = Payment::get($paymentId, $paypal->getApiContext());
        $execution = new PaymentExecution();
        $execution->setPayerId($request->get('payerID'));
        try {
            // Take the payment
            $payment->execute($execution, $paypal->getApiContext());
            try {
                $payment = Payment::get($paymentId, $paypal->getApiContext());
                $data = [
                    'transaction_id' => $payment->getId(),
                    'payment_amount' => $payment->transactions[0]->amount->total,
                    'payment_status' => $payment->getState(),
                    'invoice_id' => $payment->transactions[0]->invoice_number
                ];
                if (is_array($data) !== false && $data['payment_status'] === 'approved') {
                    //Le paiement a été ajouté avec succès, redirigez-le vers la page de fin de paiement.

                    $Newpayment = new PaymentPaypal();

                    $Newpayment->setUser($this->getUser())
                        ->setInvoiceId($data['invoice_id'])
                        ->setPaymentAmount($data['payment_amount'])
                        ->setPaymentStatus($data['payment_status'])
                        ->setTransactionId($data['transaction_id']);

                    $user = $userRepository->find($this->getUser()->getId());

                    $user->setIsLivre(null);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($Newpayment);
                    $em->persist($user);
                    $em->flush();

                    $this->addFlash("success", "Vous pouvez dès maintenant ajouter un nouveau livre sur la plate-forme.");

                    return new Response(json_encode($this->generateUrl("ajouter_livre")));

                } else {
                    // Paiement échoué
                    return new Response(json_encode($this->generateUrl("ajouter_livre")));
                }
            } catch (Exception $e) {
                // Impossible de récupérer le paiement depuis PayPal
                return new Response(json_encode('Une erreur inconnue est survenu avec le service PayPal veuillez réessayer ultérieurement.'));
            }
        } catch (Exception $e) {
            // Échec du paiement
            return new Response(json_encode($this->generateUrl("ajouter_livre")));
        }

    }
    
    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Route("/coupon-verefi/for_book", name="coupon_verefiLivre", methods={"POST"})
     * @param CouponLivreRepository $couponRepository
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function coupon_verefi(CouponLivreRepository $couponRepository, UserRepository $userRepository, Request $request) {

    
       if($this->isCsrfTokenValid('promo', $request->get("_token"))) {

          $coupon = $couponRepository->findOneBy(['coupon' => $request->get('_promo')]);
           

            if($coupon) {
                
                $expire = $coupon->getExpirdateAt() >= new \DateTime();
                
                if(!$expire) {
                    return new Response(json_encode(['status' => 'errors', 'request' => 'Le code coupon à expiré']));
                }
                
                if($coupon->getIsValid() === true) {
                    return new Response(json_encode(['status' => 'errors', 'request' => 'Ce code promo à déjà été utilisé']));
                }
                
                $allcoupons = new AllCoupons();

                $user = $userRepository->find($this->getUser()->getId());

                $user->setIsLivre(null);
                
                $coupon->setIsValid('1');
                
                $allcoupons->setUserId($this->getUser()->getId());
               
                $allcoupons->setPromoId($coupon->getId());

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->persist($coupon);
                $em->persist($allcoupons);
                $em->flush();

                $this->addFlash('success',
                    'Vous pouvez dès maintenant ajouter un nouveau livre sur la plate-forme.');

                return new Response(json_encode(['status' => 'success', 'redirect' => $this->generateUrl('ajouter_livre')]));

            }

            return new Response(json_encode(['status' => 'errors', 'request' => 'Le code coupon que vous avez saisi n\'est pas valide où expiré']));

      }

        return new Response(json_encode(['status'=>'errors', 'message' => "Le token de sécurité n'est pas valide"]));

    }

}
