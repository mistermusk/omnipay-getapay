<?php

namespace Omnipay\Getapay\Message;

use Omnipay\Common\Message\AbstractResponse;

class PayoutResponse extends AbstractResponse
{

    public function isSuccessful()
    {
        if (isset($this->data['success'])) {
            if ($this->data['success']) {
                return true;
            }
        }
    }

    public function getMessage()
    {
        return isset($this->data['message']) ? json_encode($this->data['message']) : null;
    }

}
