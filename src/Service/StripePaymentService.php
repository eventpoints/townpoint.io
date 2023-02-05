<?php

namespace App\Service;

use ArrayObject;
use Payum\Core\Model\PaymentInterface;

class StripePaymentService
{


    public function subscribeUserToStandardPlan()
    {
        /** @var PaymentInterface $payment */
        $payment->setDetails(new ArrayObject([
            'amount' => 2000,
            'currency' => 'USD',

            // everything in this section is never sent to the payment gateway
            'local' => [
                'save_card' => true,
                'customer' => ['plan' => $plan['id']],
            ],
        ]));
    }

}