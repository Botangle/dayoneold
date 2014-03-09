<?php
//Yii::import('ext.BMConstants');
include('BMConstants.php');

class PaypalproController
{
    /**
     * Send HTTP POST Request
     *
     * @param    string    The API method name
     * @param    string    The POST Message fields in &name=value pair format
     * @return    array    Parsed HTTP Response body
     */
    function PPHttpPost($methodName_, $nvpStr_)
    {
        /* Set up your API credentials, PayPal end point, and API version.*/
        $API_UserName = urlencode(BMConstants::API_USER_NAME);
        $API_Password = urlencode(BMConstants::API_PASSWORD);
        $API_Signature = urlencode(BMConstants::API_SIGNATURE);
        $API_Endpoint = BMConstants::API_END_POINT;
        $environment = BMConstants::ENVIRONMENT;
        if ("sandbox" === $environment) {
            $API_Endpoint = BMConstants::API_END_POINT_SANDBOX;
        }
        if ("beta-sandbox" === $environment) {
            $API_Endpoint = BMConstants::API_END_POINT_BETA_SANDBOX;
        }

        $version = urlencode(BMConstants::API_VERSION);
        /* Set the curl parameters.*/
        //echo $API_Endpoint; die;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        /* Turn off the server and peer verification (TrustManager Concept).*/
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        /*Set the API operation, version, and API signature in the request.*/
        $nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";
        /* Set the request as a POST FIELD for curl.*/
        curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
        /*Get response from the server.*/
        $httpResponse = curl_exec($ch);

        if (!$httpResponse) {
            exit("$methodName_ failed: " . curl_error($ch) . '(' . curl_errno($ch) . ')');
        }
        /*Extract the response details.*/
        $httpResponseAr = explode("&", $httpResponse);
        $httpParsedResponseAr = array();
        foreach ($httpResponseAr as $i => $value) {
            $tmpAr = explode("=", $value);
            if (sizeof($tmpAr) > 1) {
                $httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
            }
        }
        if ((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
            exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
        }
        return $httpParsedResponseAr;
    }

    /* FUNCTION TO SET REQUEST FIELDS */
    function setRequestFields($cartSession, $totalcartprice)
    {
        //$paymentCurrency = GeneralComponent::getPaymentCurrency();
        //$statename = GeneralComponent::getSingleStateName($cartSession['bill_state']);

        /* SET REQUEST-SPECIFIC FIELDS.*/
        $paymentType = urlencode('Sale');
        $firstName = urlencode($cartSession['fname']);
        $lastName = urlencode($cartSession['lname']);
        $creditCardType = urlencode('VISA');
        $creditCardNumber = urlencode($cartSession['acc_number']);
        $expDateMonth = $cartSession['expiration_month'];
        /* MONTH MUST BE PADDED WITH LEADING ZERO  */
        $padDateMonth = urlencode(str_pad($expDateMonth, 2, '0', STR_PAD_LEFT));
        $expDateYear = urlencode($cartSession['expiration_year']);
        $cvv2Number = urlencode($cartSession['card_security_code']);
        $address1 = urlencode($cartSession['bill_addressline1']);
        $address2 = urlencode($cartSession['bill_addressline2']);
        $city = urlencode($cartSession['bill_city']);
        $state = urlencode($cartSession['bill_state']);
        $zip = urlencode($cartSession['bill_zip']);
        $country = urlencode($cartSession['bill_country']); /* US OR OTHER VALID COUNTRY CODE */
        $amount = urlencode($cartSession['payamount']);
        $currencyID = urlencode($cartSession['paymentCurrency']); /* OR OTHER CURRENCY ('GBP', 'EUR', 'JPY', 'CAD', 'AUD') */
        /*Add request-specific fields to the request string.*/
        $nvpStr = "&PAYMENTACTION=$paymentType&AMT=$amount&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber" .
            "&EXPDATE=$padDateMonth$expDateYear&CVV2=$cvv2Number&FIRSTNAME=$firstName&LASTNAME=$lastName" .
            "&STREET=$address1&CITY=$city&STATE=$state&ZIP=$zip&COUNTRYCODE=$country&CURRENCYCODE=$currencyID";
        /*Execute the API operation; see the PPHttpPost function above. */
        $httpParsedResponseAr = PaypalproController::PPHttpPost('DoDirectPayment', $nvpStr);

        if ("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
            return $httpParsedResponseAr;
        } else {
            return "FALIURE";
        }
    }

}

?>