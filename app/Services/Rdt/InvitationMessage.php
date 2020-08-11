<?php

namespace App\Services\Rdt;

class InvitationMessage {

    public function messageWa($name, $hostName, $registrationCode){

        $message  = 'Yth. '.$name.' Sampurasun, Anda diundang untuk melakukan Tes Masif COVID-19 oleh '.$hostName;
        $message .= ' Silakan buka tautan https://s.id/tesmasif2 dan masukkan Nomor Pendaftaran berikut: ';
        $message .= $registrationCode.' untuk melihat undangan. Hatur nuhun';

        return $message;
    }

    public function messageSms($hostName, $registrationCode){

        $message  = 'Sampurasun. Anda diundang Tes Masif COVID-19 ';
        $message .= $hostName .'.Buka tautan s.id/tesmasif1 dan input nomor: ';
        $message .= $registrationCode.' untuk melihat undangan.';

        return $message;
    }

}
