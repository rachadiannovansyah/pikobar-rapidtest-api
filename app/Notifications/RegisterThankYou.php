<?php

namespace App\Notifications;

use App\Channels\WhatsappChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegisterThankYou extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [WhatsappChannel::class];
    }

    public function toWhatsapp($notifiable)
    {
        return <<<EOT
*Yth. {$notifiable->name}*

Terima kasih telah melakukan pendaftaran untuk mengikuti Tes Masif COVID-19 PIKOBAR Provinsi Jawa Barat.

Nomor Pendaftaran Anda adalah: *{$notifiable->registration_code}*

*Informasi Penting*

*1. Simpan Nomor Pendaftaran Anda*
Nomor Pendaftaran digunakan untuk mendapatkan undangan dan hasil test.

*2. Cek status pendaftaran Anda melalui website*
https://tesmasif.pikobar.jabarprov.go.id

*3. Jangan membagikan nomor pendaftaran ini kepada siapapun.*
Pastikan identitas Anda tidak digunakan orang lain dan hasil test tidak tertukar.

*4. Unduh aplikasi PIKOBAR (Android/iOS)*
Untuk mengakses informasi perkembangan terkini penangangan COVID-19 di Jawa Barat, unduh di https://bit.ly/PIKOBAR-V1

*5. Informasi dan pertanyaan lebih lanjut hubungi Pusat Bantuan PIKOBAR*
Hotline 08112093306 atau Gugus Tugas/Dinas Kesehatan Kota/Kabupaten/Provinsi setempat.

Hatur nuhun.
EOT;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
