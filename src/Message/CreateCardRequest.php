<?php

namespace Omnipay\Moneris\Message;

class CreateCardRequest extends AbstractRequest
{
    public function getData()
    {
        $data = null;
        $this->getCard()->validate();

        if ($this->getCard()) {

            $request = new \SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><request></request>');
            $request->addChild('store_id', $this->getMerchantId());
            $request->addChild('api_token', $this->getMerchantKey());

            $res_add_cc = $request->addChild('res_add_cc');
            $res_add_cc->addChild('cust_id', $this->getCard()->getBillingName());
            $phone = $this->getCard()->getBillingPhone() != '' ? $this->getCard()->getBillingPhone() : 'NA';
            $res_add_cc->addChild('phone', $phone);
            $res_add_cc->addChild('email', $this->getCard()->getEmail());
            $res_add_cc->addChild('note', 'NA');
            $res_add_cc->addChild('pan', $this->getCard()->getNumber());
            $res_add_cc->addChild('expdate', $this->getCard()->getExpiryDate('my'));
            $res_add_cc->addChild('crypt_type', 1);

            $avs_info = $res_add_cc->addChild('avs_info');
            $avs_info->addChild('avs_street_number', 'NA');
            $avs_street_name = $this->getCard()->getBillingAddress1() != '' ? $this->getCard()->getBillingAddress1() : 'NA';
            $avs_info->addChild('avs_street_name', $avs_street_name);
            $avs_info->addChild('avs_zipcode', $this->getCard()->getBillingPostcode());

            $data = $request->asXML();
        }

        return preg_replace('/\n/', ' ', $data);
    }
}