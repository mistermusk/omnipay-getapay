<?php

namespace Omnipay\Getapay;

use Omnipay\Common\AbstractGateway;

class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'Getapay';
    }


    public function getApikey()
    {
        return $this->getParameter('api_key');
    }

    public function setApikey($value)
    {
        return $this->setParameter('api_key', $value);
    }

    public function getSecretkey()
    {
        return $this->getParameter('secret_key');
    }

    public function setSecretkey($value)
    {
        return $this->setParameter('secret_key', $value);
    }

    public function getApikeysecond()
    {
        return $this->getParameter('api_key_second');
    }

    public function setApikeysecond($value)
    {
        return $this->setParameter('api_key_second', $value);
    }

    public function getSecretkeysecond()
    {
        return $this->getParameter('secret_key_second');
    }

    public function setSecretkeysecond($value)
    {
        return $this->setParameter('secret_key_second', $value);
    }

    public function isSignatureValid(array $callbackData, $first_level)
    {
        try {
            $fieldsToSign = [
                isset($callbackData['order_id']) ? $callbackData['order_id'] : '',
                isset($callbackData['create_date']) ? $callbackData['create_date'] : '',
                isset($callbackData['result_date']) ? $callbackData['result_date'] : '',
                isset($callbackData['amount']) ? $callbackData['amount'] : '',
                isset($callbackData['payment_id']) ? $callbackData['payment_id'] : '',
                isset($callbackData['amount_gross']) ? $callbackData['amount_gross'] : '',
                isset($callbackData['card_pan']) ? $callbackData['card_pan'] : '',
                isset($callbackData['currency']) ? $callbackData['currency'] : '',
                isset($callbackData['transaction_status']) ? $callbackData['transaction_status'] : '',
                isset($callbackData['success']) ? ($callbackData['success'] ? 'true' : 'false') : ''
            ];

            $secret = $this->getSecretkeysecond();
            if ($first_level){
                $secret = $this->getSecretkey();
            }
            $dataString = implode('|', $fieldsToSign);
            $computedSignature = hash_hmac('sha256', $dataString, $secret);
            return $computedSignature === (isset($callbackData['signature']) ? $callbackData['signature'] : '');
        } catch (Exception $e) {
            return false;
        }
    }


    public function purchase(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\Getapay\Message\PurchaseRequest', $parameters)
            ->setApikey($this->getApikey())
            ->setSecretkey($this->getSecretkey())
            ->setApikeysecond($this->getApikeysecond())
            ->setSecretkeysecond($this->getSecretkeysecond());
    }

    public function payout(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\Getapay\Message\PayoutRequest', $parameters)
            ->setApikey($this->getApikey())
            ->setSecretkey($this->getSecretkey());


    }
}
