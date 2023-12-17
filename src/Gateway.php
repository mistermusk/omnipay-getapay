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
