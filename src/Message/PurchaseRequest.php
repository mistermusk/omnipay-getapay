<?php


namespace Omnipay\Getapay\Message;

use Omnipay\Common\Message\AbstractRequest;

class PurchaseRequest extends AbstractRequest
{


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

    public function getCurrency()
    {
        return $this->getParameter('currency');
    }

    public function setCurrency($value)
    {
        return $this->setParameter('currency', $value);
    }

    public function getFailureurl()
    {
        return $this->getParameter('failure_url');
    }

    public function setFailureurl($value)
    {
        return $this->setParameter('failure_url', $value);
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


    public function getResulturl()
    {
        return $this->getParameter('result_url');
    }

    public function setResulturl($value)
    {
        return $this->setParameter('result_url', $value);
    }

    public function getSuccessurl()
    {
        return $this->getParameter('success_url');
    }

    public function setSuccessurl($value)
    {
        return $this->setParameter('success_url', $value);
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

    public function getPhone()
    {
        return $this->getParameter('phone');
    }

    public function setPhone($value)
    {
        return $this->setParameter('phone', $value);
    }


    protected function createSignature($data, $secretKey) {
        $values = array_values($data);
        sort($values, SORT_STRING);
        $joinedParams = implode("|", $values);
        return hash_hmac('sha256', $joinedParams, hex2bin($secretKey));
    }

    public function getData()
    {
        $data = [
            'project' => $this->getApikey(),
            'currency' => $this->getCurrency(),
            'description' => 'getapay',
            'failure_url' => $this->getFailureurl(),
            'ip' => '8.8.8.8',
            'order_id' => $this->getTx(),
            'price' => $this->getAmount(),
            'result_url' => $this->getResulturl(),
            'success_url' => $this->getSuccessurl(),
            'user_contact_email' => $this->getEmail(),
            'user_name' => $this->getName(),
            'user_phone' => $this->getPhone(),
        ];

        return array_filter($data, function ($value) {
            return $value !== null;
        });
    }
    public function sendData($data)
    {
        $data['signature'] = $this->createSignature($data, $this->getSecretkey());
        $postData = json_encode($data);

        $httpResponse = $this->httpClient->request('POST', 'https://api.example.com/dev/invoices', [],  $postData);
        return $this->createResponse($httpResponse->getBody()->getContents());

    }


    protected function createResponse($data)
    {
        return $this->response = new PurchaseResponse($this, json_decode($data, true));
    }

}
