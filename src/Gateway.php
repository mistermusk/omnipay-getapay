<?php

namespace Omnipay\Getapay;

use Omnipay\Common\AbstractGateway;

class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'Getapay';
    }
    private $keys = [];
    public function setKeys($apiData)
    {
        $this->keys = $apiData;
    }

    public function getKeys()
    {
        return $this->keys;
    }

    public function formatLevel($level){
        if ($level){
            return 'first_level';
        }
        return 'second_level';
    }

    protected function createSignature($data, $secretKey) {
        $values = array_values($data);
        sort($values, SORT_STRING);
        $joinedParams = implode("|", $values);
        return hash_hmac('sha256', $joinedParams, hex2bin($secretKey));
    }

    public function isSignatureValidDeposit($sign, $data, $level, $method, $currency)
    {
        try {
            $data['signature'] = null;
            $data = array_filter($data, function ($value) {
                return $value !== null && !is_array($value);
            });

            $secret = (string) $this->getKeys()['api_deposit'][$this->formatLevel($level)][$method][$currency]['secret_key'];

            $computedSignature = $this->createSignature($data, $secret);
            return $computedSignature === $sign;
        } catch (Exception $e) {
            return false;
        }
    }


    public function purchase(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\Getapay\Message\PurchaseRequest', $parameters)
            ->setKeys($this->getKeys());
    }

    public function payout(array $parameters = [])
    {
        return $this->createRequest('\Omnipay\Getapay\Message\PayoutRequest', $parameters)
            ->setKeys($this->getKeys());


    }
}
