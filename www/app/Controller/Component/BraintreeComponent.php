<?php
/**
 * BraintreeComponent.php
 *
 * @author: David Baker <dbaker@acorncomputersolutions.com
 * Date: 7/21/14
 * Time: 5:52 PM
 */

App::uses('Component', 'Controller');
App::uses('StripeComponent', 'Stripe.Controller/Component');

class BraintreeComponent extends StripeComponent {

    /**
     * Default environment: sandbox or live
     *
     * @var string
     * @access public
     */
    public $mode = 'sandbox';

    /**
     * The required Stripe secret API key
     *
     * @var string
     * @access public
     */
    public $key = null;

    /**
     * Controller startup. Loads the Stripe API library and sets options from
     * APP/Config/bootstrap.php.
     *
     * @param Controller $controller Instantiating controller
     * @return void
     * @throws CakeException
     * @throws CakeException
     */
    public function startup(Controller $controller) {
        $this->Controller = $controller;

        if (!class_exists('Braintree_Configuration')) {
            throw new CakeException('Braintree PHP library could not be loaded.');
        }

        // if mode is set in bootstrap.php, use it. otherwise, Test.
        $mode = Configure::read('Braintree.environment');
        if ($mode) {
            $this->mode = $mode;
            Braintree_Configuration::environment($this->mode);
        }

        // set other items as well
        Braintree_Configuration::merchantId(Configure::read('Braintree.merchantId'));
        Braintree_Configuration::publicKey(Configure::read('Braintree.publicKey'));

        // set the Braintree private key
        $this->key = Configure::read('Braintree.privateKey');
        if (!$this->key) {
            throw new CakeException('Braintree private key is not set.');
        }
        Braintree_Configuration::privateKey($this->key);
    }

    /**
     * Generates a client token so payments can be sent
     *
     * @return mixed
     */
    public function generateToken()
    {
        return Braintree_ClientToken::generate();
    }
}