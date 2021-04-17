<?php

namespace App\Services\Paypal;

CONST username_paypal = "contact_api1.terhoma.org";
CONST password_paypal = "ZDUD22XKYZKUYG9A";
CONST signature_paypal = "AapgdQu7cTX3IY9.CSi0zeimKuEqAIsiBzcrnjd4OMlQsh.U6n8Xz2V1";

class PaypalSubscription
{

    private $username;
    private $password;
    private $signature;
    private $offers;

    /**
     * PaypalSubscription constructor.
     * @param $username
     * @param $password
     * @param $signature
     * @param null $offers
     */
    public function __construct($username, $password, $signature, $offers) {

        $this->username = $username;
        $this->password = $password;
        $this->signature = $signature;
        $this->offers = $offers;
    }

    /**
     * @return array
     */
    static function getOffers() {

        return [
            // 'inscription lecteur' => [
            //     'price' => 12,
            //     'period' => 'Year'
            // ],
            'inscription auteur' => [
                'price' => 60,
                'period' => 'Year'
            ]
        ];

    }

    /**
     * @param array $options
     * @return array
     */
    public function nvp($options = []) {

        $curl = curl_init();
        $data = [
            'USER' => $this->username,
            'PWD' => $this->password,
            'SIGNATURE' => $this->signature,
            'METHOD' => 'SetExpressCheckout',
            'VERSION' => 86,
        ];

        $data = array_merge($data, $options);

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api-3t.paypal.com/nvp",
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => http_build_query($data)
        ]);

        $response = curl_exec($curl);

        $responseArray = [];

        parse_str($response, $responseArray);

        return $responseArray;

    }


    /**
     * @param $offer
     * @return mixed
     */
    public function subscribe($offer) {

        if(!$this->offers[$offer]) {
            die('Cette offre nexiste pas');
        }

        $offers = $this->offers[$offer];
        $data = [
            'METHOD' => 'SetExpressCheckout',
            'PAYMENTREQUEST_0_AMT' => $offers['price'],
            'PAYMENTREQUEST_0_ITEMAMT' => $offers['price'],
            'PAYMENTREQUEST_0_CURRENCYCODE' => 'EUR',
            'PAYMENTREQUEST_0_CUSTOM' => $offer,
            'L_BILLINGTYPE0' => 'RecurringPayments',
            'L_BILLINGAGREEMENTDESCRIPTION0' => $offer,
            'cancelUrl' => 'http://localhost:8000/test',
            'returnUrl' => 'http://localhost:8000/process'
        ];

        $response = $this->nvp($data);

        if(empty($response['TOKEN'])) {
            die($response['L_LONGMESSAGE0']);
        }

        $token = $response['TOKEN'];

        return $token;

//        $url = "https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=$token";
//
//        dump($url, $offers);

    }

    /**
     * @param $token
     * @return array
     */
    public function getChechoutDetail($token) {

        $data = [
            'METHOD' => 'GetExpressCheckoutDetails',
            'TOKEN' => $token
        ];

        return $this->nvp($data);

    }

    /**
     * @param $token
     * @return array
     * @throws \Exception
     */
    public function IsValidsubscribe($token) {

        $detail = $this->getChechoutDetail($token);

        if($detail['ACK'] === 'Failure') {
            die("Failure !");
        }

        $offer = $this->offers[$detail['PAYMENTREQUEST_0_CUSTOM']];

        if(empty($offer)) {
            die("L'offre n'existe pas !");
        }

        $period = new \DateInterval('P1Y');

        $start = (new \DateTime())->add($period)->getTimestamp();

        $responde = $this->nvp([
            'METHOD' => 'CreateRecurringPaymentsProfile',
            'TOKEN' => $token,
            'PAYERID' => $detail['PAYERID'],
            'DESC' => $detail['PAYMENTREQUEST_0_CUSTOM'],
            'AMT' => $offer['price'],
            'BILLINGPERIOD' => $offer['period'],
            'BILLINGFREQUENCY' => 1,
            'CURRENCYCODE' => 'EUR',
            'COUNTRYCODE' => 'FR',
            'MAXFAILEDPAYMENTS' => 3,
            'PROFILESTARTDATE' => gmdate('Y-m-d\TH:i:s\z', $start),
            'INITAMT' => $offer['price']
        ]);

        if($responde['ACK'] === 'Success') {
            
            return ['payerid' => $detail['PAYERID'], 'profileid' => $responde['PROFILEID']];

        } else {

            die($responde['L_LONGMESSAGE0']);

        }

    }


    /**
     * @param $profile_id
     * @return array
     */
    public function getProfilDetail($profile_id) {

        return $this->nvp([
            'METHOD' => 'getrecurringpaymentsprofiledetails',
            'PROFILEID' => $profile_id
        ]);

    }

    /**
     * @param $data
     * @return bool
     */
    public function CheckIPN($data) {

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://www.paypal.com/cgi-bin/webscr?cmd=_notify-validate&" . http_build_query($data),
            CURLOPT_RETURNTRANSFER => 1,
        ]);

        $response = curl_exec($curl);

        return $response === 'VERIFIED';

    }


}
