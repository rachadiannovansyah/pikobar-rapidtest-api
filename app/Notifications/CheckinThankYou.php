<?php

namespace App\Notifications;

use App\Channels\WhatsappChannel;
use App\Entities\RdtEvent;
use App\Entities\RdtInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CheckinThankYou extends Notification
{
    use Queueable;

    /**
     * @var \App\Entities\RdtEvent
     */
    public $rdtEvent;

    /**
     * @var \App\Entities\RdtInvitation
     */
    public $rdtInvitation;

    /**
     * Create a new notification instance.
     *
     * @param \App\Entities\RdtEvent $rdtEvent
     * @param \App\Entities\RdtInvitation $rdtInvitation
     */
    public function __construct(RdtEvent $rdtEvent, RdtInvitation $rdtInvitation)
    {
        $this->rdtEvent = $rdtEvent;
        $this->rdtInvitation = $rdtInvitation;
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

Terima kasih telah menghadiri Tes Masif COVID-19 PIKOBAR Provinsi Jawa Barat.

Nomor Pendaftaran: *{$notifiable->registration_code}*
Tanggal/Waktu: *{$this->rdtInvitation->attended_at->setTimezone('Asia/Jakarta')}*
Tempat: *{$this->rdtEvent->event_location}*

Selama menunggu hasil tes, Anda diharapkan tetap menggunakan masker, menjaga jarak dan protokol kesehatan, serta melakukan pola hidup bersih dan sehat (PHBS).

*Informasi Penting*

*1. Simpan Nomor Pendaftaran Anda*
Nomor Pendaftaran digunakan untuk mendapatkan hasil test.

*2. Hasil tes akan diberitahukan melalui SMS, Whatsapp, dan website.*
Buka https://tesmasif.pikobar.jabarprov.go.id dan masukkan Nomor Pendaftaran.

*3. Jangan membagikan nomor pendaftaran ini kepada orang lain.*
Pastikan identitas Anda tidak digunakan orang lain.

*4. Unduh aplikasi PIKOBAR (Android/iOS)*
Untuk mengakses informasi perkembangan terkini penanganan COVID-19 di Jawa Barat, unduh di https://bit.ly/PIKOBAR-V1

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
