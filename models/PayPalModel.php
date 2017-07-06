<?php

namespace Plugin\Track\Models;

use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use Plugin\Track\Config;
use Plugin\Track\RestBadRequest;
use Plugin\Track\RestResponseError;

class PayPalModel
{

    private static $payment;
    private static $context;

    /**
     * Inits static properties, such as context
     */
    public static function init() {
        $config = Config::getPayPal();

        $oauth = new \PayPal\Auth\OAuthTokenCredential(
            $config['clientId'],
            $config['secret']
        );

        self::$context = new \PayPal\Rest\ApiContext($oauth);
    }

    public static function executePayment($body)
    {
        if (!$body) {
            return new RestBadRequest(['error' => 'Missing POST-body']);
        }

        if (empty($body['paymentID']) || empty($body['payerID'])) {
            return new RestBadRequest(['error' => 'Missing field `paymentID` or `payerID` in POST-body']);
        }

        $payment = Payment::get($body['paymentID'], self::$context);

        $execution = self::createPaymentExecution($body['payerID']);

        $transaction = new Transaction();
        $amount = new Amount();
        $amount->setCurrency('EUR')
            ->setTotal(0.2);
        $transaction->setAmount($amount);

        $execution->addTransaction($transaction);

        $result = $payment->execute($execution, self::$context);
        $payment = Payment::get($body['paymentID'], self::$context);

        return $payment;
    }



    /**
     *
     * @return \PayPal\Api\Payment
     * @throws \PayPal\Exception\PayPalConnectionException
     */
    public static function createPayment()
    {
        $list = new ItemList();

        // Create the item
        $item = new Item();
        $item
            ->setName("Introduction to the C.L.E.A.R. Mindset")
            ->setDescription("Introductory course, preparing you for the CLEAR Mindset")
            ->setCurrency('EUR')
            ->setQuantity(1)
            ->setPrice(0.2);

        $list->setItems([$item]);

        $amount = new Amount();
        $amount->setCurrency('EUR')
            ->setTotal(0.2);


        $payment = new \PayPal\Api\Payment();
        $payment->setIntent('sale')
            ->setRedirectUrls(self::createRedirects(ipConfig()->baseUrl()))
            ->setPayer(self::createPayer())
            ->setTransactions([self::createTransaction($amount, $list)]);

        $payment->create(self::$context);

        assert($payment->getState() == 'created', 'Expect PayPal payment to be created');

        return $payment;
    }

    /**
     *
     * @param string $payerID
     * @return \PayPal\Api\PaymentExecution
     */
    private static function createPaymentExecution($payerID) {
        $execution = new PaymentExecution();
        $execution->setPayerId($payerID);
        return $execution;
    }

    /**
     * @return \PayPal\Api\Payer
     */
    private static function createPayer()
    {
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        return $payer;
    }

    /**
     * @param \PayPal\Api\ItemList $items
     * @return \PayPal\Api\Transaction
     */
    private static function createTransaction($amount, $items = null)
    {
        $trans = new Transaction();
        $trans
            ->setAmount($amount)
            ->setDescription("Grooa Online course")
            ->setInvoiceNumber(uniqid());

        if (!empty($items)) {
            $trans->setItemList($items);
        }

        return $trans;
    }

    /**
     * @param string $baseUrl
     * @return \PayPal\Api\RedirectUrls
     */
    private static function createRedirects($baseUrl)
    {
        $redirect = new RedirectUrls();
        $redirect
            ->setReturnUrl("$baseUrl/paypal/success-payment")
            ->setCancelUrl("$baseUrl/paypal/cancel-payment");

        return $redirect;
    }
}
PayPalModel::init();