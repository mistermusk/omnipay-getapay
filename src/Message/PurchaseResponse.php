<?php


namespace Omnipay\Getapay\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    public function isSuccessful()
    {
        return isset($this->data[]['payment_url']);
    }

    public function getRedirectUrl()
    {
        return isset($this->data['payment_url']) ? $this->data['payment_url'] : null;
    }

    public function getMessage()
    {
        return json_encode($this->data);
    }

}
