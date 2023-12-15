<?php

namespace Omnipay\Getapay;

use Omnipay\Common\AbstractGateway;

class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'Getapay';
    }

    public function getEmail()
    {
        return $this->getParameter('email');
    }

    public function setEmail($value)
    {
        return $this->setParameter('email', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('password');
    }

    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }


    public function purchase(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\Getapay\Message\PurchaseRequest', $parameters)
            ->setEmail($this->getEmail())
            ->setPassword($this->getPassword());
    }

    public function payout(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\Getapay\Message\PayoutRequest', $parameters)
            ->setEmail($this->getEmail())
            ->setPassword($this->getPassword());
    }
}
