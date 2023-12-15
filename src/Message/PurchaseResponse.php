<?php


namespace Omnipay\Getapay\Message;

use Omnipay\Common\Message\AbstractResponse;

class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    public function isSuccessful()
    {
        return isset($this->data['payment_url']);
    }

    public function getMessage()
    {
        return json_encode($this->data);
    }

}
