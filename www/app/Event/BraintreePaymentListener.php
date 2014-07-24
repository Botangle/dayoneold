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
        $nonce = $event->data['nonce'];

        $result = Braintree_Transaction::sale(array(
                'amount' => '100.00',
                'paymentMethodNonce' => $nonce,
            ));

        // or update the transaction_key of our $event->subject() with the info we get back from Braintree
        if($result->success) {
            $ourTransaction = $event->subject();
            $ourTransaction->data['Transaction']['transaction_key'] = $result->transaction->id;
            return;
        } else {
            // otherwise, stop everything
            $event->stopPropagation();
        }
    }
}