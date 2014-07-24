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

    /**
     * The charge method prepares data for Stripe_Charge::create and attempts a
     * transaction using the
     *
     * @param string $accessToken Must be an OAuth key received from Stripe Connect flow, **not** a secret key
     * @param array	$data Must contain 'amount' and 'stripeToken'.
     * @return array $charge if success, string $error if failure.
     * @throws CakeException
     * @throws CakeException
     */
    public function connectCharge($accessToken, $data) {

        // @TODO: it'd be lovely to refactor most of this out and just leave it in the Stripe Component
        // however, it's not quite flexible enough for that at the moment.  Mainly need to be able to adjust
        // the chargeData array before it gets sent off to Stripe  Then we'd be golden

        // @TODO: we should probably be a lot more careful on our checks on all of the stuff below
        // we want to create a customer access token that will work with our authenticated Stripe Connect tutor info
        $customerToken = Stripe_Token::create(
            array("customer" => $data['stripeCustomer']), // the customer in Botangle's customer list we want to charge
            $accessToken // tutor's access token from the Stripe Connect flow
        );



        // this customer token we then want to swap in to the normal $data array
        // but we apparently want it to be a card token, not a customer token
        unset($data['stripeCustomer']);

        // we name it stripeToken for this plugin's sake, not Stripe's
        $data['stripeToken'] = $customerToken->id;



        // $data MUST contain 'stripeToken' or 'stripeCustomer' (id) to make a charge.
        if (!isset($data['stripeToken']) && !isset($data['stripeCustomer'])) {
            throw new CakeException('The required stripeToken or stripeCustomer fields are missing.');
        }

        // if amount is missing or not numeric, abort.
        if (!isset($data['amount']) || !is_numeric($data['amount'])) {
            throw new CakeException('Amount is required and must be numeric.');
        }

        // set the (optional) description field to null if not set in $data
        if (!isset($data['description'])) {
            $data['description'] = null;
        }

        // set the (optional) capture field to null if not set in $data
        if (!isset($data['capture'])) {
            $data['capture'] = null;
        }

        // format the amount, in cents.
        $data['amount'] = $data['amount'] * 100;

        // Custom Tweak for Botangle here
        Stripe::setApiKey($accessToken);
        $error = null;

        $chargeData = array(
            'amount' => $data['amount'],
            'currency' => $this->currency,
            'description' => $data['description'],
            'capture' => $data['capture']
        );

        // Custom Tweak for Botangle here
        // format the fee amount, in cents, if any
        if(isset($data['application_fee'])) {
            $chargeData['application_fee'] = $data['application_fee'] * 100;
        }

        if (isset($data['stripeToken'])) {
            $chargeData['card'] = $data['stripeToken'];
        } else {
            $chargeData['customer'] = $data['stripeCustomer'];
        }

        try {
            $charge = Stripe_Charge::create($chargeData);

        } catch(Stripe_CardError $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            CakeLog::error(
                'Charge::Stripe_CardError: ' . $err['type'] . ': ' . $err['code'] . ': ' . $err['message'],
                'stripe'
            );
            $error = $err['message'];

        } catch (Stripe_InvalidRequestError $e) {
            $body = $e->getJsonBody();
            $err = $body['error'];
            CakeLog::error(
                'Charge::Stripe_InvalidRequestError: ' . $err['type'] . ': ' . $err['message'],
                'stripe'
            );
            $error = $err['message'];

        } catch (Stripe_AuthenticationError $e) {
            CakeLog::error('Charge::Stripe_AuthenticationError: API key rejected!', 'stripe');
            $error = 'Payment processor API key error.';

        } catch (Stripe_ApiConnectionError $e) {
            CakeLog::error('Charge::Stripe_ApiConnectionError: Stripe could not be reached.', 'stripe');
            $error = 'Network communication with payment processor failed, try again later';

        } catch (Stripe_Error $e) {
            CakeLog::error('Charge::Stripe_Error: Stripe could be down.', 'stripe');
            $error = 'Payment processor error, try again later.';

        } catch (Exception $e) {
            CakeLog::error('Charge::Exception: Unknown error.', 'stripe');
            $error = 'There was an error, try again later.';
        }

        if ($error !== null) {
            // an error is always a string
            return (string)$error;
        }

        CakeLog::info('Stripe: charge id ' . $charge->id, 'stripe');

        return $this->_formatResult($charge);
    }

} 