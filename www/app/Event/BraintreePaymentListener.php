<?php
/**
 * PaymentListener.php
 *
 * @author: David Baker <dbaker@acorncomputersolutions.com
 * Date: 7/23/14
 * Time: 8:08 PM
 */

// based heavily on http://martinbean.co.uk/blog/2013/11/22/getting-to-grips-with-cakephps-events-system/

App::uses('CakeEventListener', 'Event');

class BraintreePaymentListener implements CakeEventListener {

    public function implementedEvents() {
        return array(
            'Transaction.handle_purchase' => 'handlePurchase',
        );
    }

    /**
     * Handle a purchase through Braintree
     *
     * @param $event
     */
    public function handlePurchase(CakeEvent $event)
    {
        $amount = $event->data['amount'];
        $nonce = $event->data['nonce'];
        $user = $event->data['customer'];

        $result = Braintree_Transaction::sale(array(
                'amount'                => $amount,
                'paymentMethodNonce'    => $nonce,
                'customer' => array(
                    'firstName'     => $user['name'], // argh, I'm going to be so glad when we can clean this up :-(
                    'lastName'      => $user['lname'],
                    'email'         => $user['email'],
                ),
            ));

        // or update the transaction_key of our $event->subject() with the info we get back from Braintree
        if($result->success) {
            $ourTransaction = $event->subject();
            $ourTransaction->data['Transaction']['transaction_key'] = $result->transaction->id;

            // now let's try to submit for settlement, as we've provided the good that we wanted to provide (our credits)
            $result = Braintree_Transaction::submitForSettlement($result->transaction->id);
            if($result->success) {
                return;
            } else {
                // @TODO: we'll let this go through for now, but long-term, we'd like to setup an auto-retry to ensure that settlements go through
            }
        } else {
            // otherwise, stop everything
            $event->stopPropagation();
        }
    }
}