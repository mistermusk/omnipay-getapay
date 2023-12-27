<?php


namespace Omnipay\Getapay\Message;

use Omnipay\Common\Message\AbstractRequest;

class PurchaseRequest extends AbstractRequest
{

    public function setKeys($fullKeys){
        return $this->setParameter('keys', $fullKeys);
    }

    public function getKeys()
    {
        return $this->getParameter('keys');
    }


    public function getLevel()
    {
        return $this->getParameter('level');
    }

    public function setLevel($value)
    {
        return $this->setParameter('level', $value);
    }

    public function getApikey()
    {
        return $this->getKeys()['api_deposit'][$this->getLevel()][$this->getMethod()][$this->getCurrency()]['api_key'];
    }


    public function getSecretkey()
    {
        return $this->getKeys()['api_deposit'][$this->getLevel()][$this->getMethod()][$this->getCurrency()]['secret_key'];
    }

    public function getMethod()
    {
        return $this->getParameter('method');
    }

    public function setMethod($value)
    {
        return $this->setParameter('method', $value);
    }

    public function getCurrency()
    {
        return $this->getParameter('currency');
    }

    public function setCurrency($value)
    {
        return $this->setParameter('currency', $value);
    }


    public function getTx()
    {
        return $this->getParameter('tx');
    }

    public function setTx($value)
    {
        return $this->setParameter('tx', $value);
    }

    public function getAmount()
    {
        return $this->getParameter('amount');
    }

    public function setAmount($value)
    {
        return $this->setParameter('amount', $value);
    }

    public function getEmail()
    {
        return $this->getParameter('email');
    }

    public function setEmail($value)
    {
        return $this->setParameter('email', $value);
    }

    public function getName()
    {
        return $this->getParameter('name');
    }

    public function setName($value)
    {
        return $this->setParameter('name', $value);
    }
    public function getNumbercard()
    {
        return $this->getParameter('number_card');
    }

    public function setNumbercard($value)
    {
        return $this->setParameter('number_card', $value);
    }

    public function getExpirationmonth()
    {
        return $this->getParameter('expiration_month');
    }

    public function setExpirationmonth($value)
    {
        return $this->setParameter('expiration_month', $value);
    }
    public function getExpirationyear()
    {
        return $this->getParameter('expiration_year');
    }

    public function setExpirationyear($value)
    {
        return $this->setParameter('expiration_year', $value);
    }

    public function getCvv()
    {
        return $this->getParameter('cvv');
    }

    public function setCvv($value)
    {
        return $this->setParameter('cvv', $value);
    }

    public function getPhone()
    {
        return $this->getParameter('phone');
    }

    public function setPhone($value)
    {
        return $this->setParameter('phone', $value);
    }

    public function getRedirecturl()
    {
        return $this->getKeys()['redirect_url'];
    }

    public function getCallbackurl()
    {
        return $this->getParameter('callback_url');
    }
    public function setCallbackurl($value)
    {
        return $this->setParameter('callback_url', $value);
    }


    protected function createSignature($data, $secretKey) {
        $values = array_values($data);
        sort($values, SORT_STRING);
        $joinedParams = implode("|", $values);
        return hash_hmac('sha256', $joinedParams, hex2bin($secretKey));
    }

    function formatNumber($number) {
        return number_format((float)$number, 2, '.', '');
    }


    public function getData()
    {
        $project = $this->getApikey();
        $datatoken = $this->getTokenData();

        $data = [
            'project' => $project,
            'currency' => $this->getCurrency(),
            'card_token' => $datatoken->getData()['id'],
            'description' => $project,
            'ip' => '8.8.8.8',
            'order_id' => $this->getTx(),
            'price' => $this->formatNumber($this->getAmount()),
            'result_url' => $this->getCallbackurl(),
            'success_url' => $this->getRedirecturl(),
            'failure_url' => $this->getRedirecturl(),
            'user_contact_email' => $this->getEmail(),
            'user_name' => $this->getName(),
            'user_phone' => $this->getPhone(),
        ];

        return array_filter($data, function ($value) {
            return $value !== null;
        });
    }

    public function getTokenData(){

        $project = $this->getApikey();

        $body = json_encode([
            'project' => $project,
            'number' => $this->getNumbercard(),
            'expiration_month' => $this->getExpirationmonth(),
            'expiration_year' => $this->getExpirationyear(),
            'security_code' => $this->getCvv()
        ]);
        $httpResponse = $this->httpClient->request('POST', 'https://api.payprogate.com/dev/card/getToken', [],  $body);
        return $this->createResponse($httpResponse->getBody()->getContents());
    }

    public function sendData($data)
    {
        $secret = $this->getSecretkey();
        $data['signature'] = $this->createSignature($data, $secret);
        $postData = json_encode($data);

        $httpResponse = $this->httpClient->request('POST', 'https://api.payprogate.com/dev/card/process', [],  $postData);
        return $this->createResponse($httpResponse->getBody()->getContents());

    }

    protected function createResponse($data)
    {
        return $this->response = new PurchaseResponse($this, json_decode($data, true));
    }

}

