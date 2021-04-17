<?php

namespace App\Services\Paypal;

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

CONST client_id = "AeHsP6qIlJ8smCpNirZytsCUk9PvZmP9ePVzpSFFeilinjBqa4r7mXOpynOMPN2H9XbOjijo0DuLQvJU";

CONST secret = "EGQSjUXcuhVem03uiHmqbORUx1-_uPRDLP1S1PXv1nbet7Elx8QmUu7XVwIUNfnXSHoU8yg7z6UvN6aK";

class Paypal
{


    public function __construct() {}

    /**
     * @return \PayPal\Rest\ApiContext
     */
    public function getApiContext()
    {
        $apiContext = new ApiContext(
            new OAuthTokenCredential(client_id, secret)
        );
        $apiContext->setConfig([
            'mode' => 'live'
        ]);
        return $apiContext;
    }


}
