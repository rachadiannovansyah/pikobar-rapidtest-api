<?php

namespace App\Services\Rdt;

class ReformatPhoneNumber {

    const FORMAT_SMS = 'sms';

    const FORMAT_WA = 'wa';

    private function reformatSms($phoneNumber)
    {
        if ($phoneNumber[0] == '6') {
            return substr_replace($phoneNumber,'0',0, 2);
        }

        if ($phoneNumber[0] == '+') {
            return substr_replace($phoneNumber,'0',0, 3);
        }

        return $phoneNumber;
    }

    private function reformatWa($phoneNumber)
    {
        if ($phoneNumber[0] == '0') {
            return substr_replace($phoneNumber,'62',0, 1);
        }

        if ($phoneNumber[0] == '+') {
            return substr_replace($phoneNumber,'',0, 1);
        }

        return $phoneNumber;
    }

    public function reformat($phoneNumber, $format = 'sms')
    {

        if ($format === 'wa') {

            return $this->reformatWa($phoneNumber);

        }

        return $this->reformatSms($phoneNumber);

    }


}
