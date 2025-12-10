<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewAdminReply extends Notification
{
    use Queueable;

    private $pengaduan;
    private $pesan;
    private $image;

    /**
     * Create a new notification instance.
     */
    public function __construct($pengaduan, $pesan, $image = null)
    {
        $this->pengaduan = $pengaduan;
        $this->pesan = $pesan;
        $this->image = $image;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Simpan ke database
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'id_pengaduan' => $this->pengaduan->id_pengaduan,
            'judul' => 'Balasan Baru dari Admin',
            'pesan' => 'Admin telah membalas pengaduan Anda: "' . $this->pengaduan->judul . '"',
            'url' => route('user.pengaduan.show', $this->pengaduan->id_pengaduan),
            'type' => 'reply', // penanda tipe
            'image' => $this->image,
        ];
    }
}
