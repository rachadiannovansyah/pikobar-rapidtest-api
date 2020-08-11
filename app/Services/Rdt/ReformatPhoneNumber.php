<?php

namespace App\Services\Rdt;

class ReformatPhoneNumber {


    public function reformat($phoneNumber)
    {

        if ($phoneNumber[0] == '0') {
            return substr_replace($phoneNumber,'62',0, 1);
        }

        if ($phoneNumber[0] == '+') {
            return substr_replace($phoneNumber,'',0, 1);
        }

        return $phoneNumber;

    }


}
