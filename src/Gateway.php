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

    protected function createSignature($data, $secretKey) {
        $values = array_values($data);
        sort($values, SORT_STRING);
        $joinedParams = implode("|", $values);
        return hash_hmac('sha256', $joinedParams, hex2bin($secretKey));
    }

    public function isSignatureValid(array $callbackData, $first_level)
    {
        try {
            $signature = $callbackData['signature'];
            $callbackData['signature'] = null;
            $fieldsToSign = array_filter($callbackData, function ($value) {
                return $value !== null && !is_array($value);
            });


            $secret = $this->getSecretkeysecond();
            if ($first_level){
                $secret = $this->getSecretkey();
            }
            $computedSignature = $this->createSignature($callbackData, $secret);
            return $computedSignature === $signature;
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
