<?php


namespace Omnipay\Getapay\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    public function isSuccessful()
    {
        return isset($this->data['success']) == 1;
    }

    public function getRedirectUrl()
    {
        return isset($this->data['acs']['url']) ? $this->data['acs']['url'] : null;
    }

    public function getMessage()
    {
        return json_encode($this->data);
    }

}
