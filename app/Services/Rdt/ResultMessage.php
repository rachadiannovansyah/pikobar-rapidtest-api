<?php


namespace App\Services\Rdt;


class ResultMessage
{
    public function messageSms($registrationCode)
    {
        $messageSms  = 'Sampurasun, hasil Tes COVID Anda sudah keluar.';
        $messageSms .= 'Silahkan buka tautan bit.ly/tesmasif dan input Nomor Pendaftaran';
        $messageSms .=  $registrationCode . 'untuk melihat hasil tes.';

        return $messageSms;
    }

    public function messageWa($applicantName, $registrationCode)
    {
        $messageWa   = '*Yth. '. $applicantName .'*';
        $messageWa  .= 'Sampurasun, hasil Tes COVID-19 Anda sudah keluar.';
        $messageWa  .= 'Untuk mengetahui hasilnya, silahkan buka tautan https://bit.ly/tesmasif dan';
        $messageWa  .= 'masukkan Nomor Pendaftaran berikut: *'. $registrationCode .'* untuk mendapatkan hasil tes.';
        $messageWa  .= 'Hatur nuhun.';

        return $messageWa;
    }
}
