<?php

namespace App\Notifications;

use App\Models\Materi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MateriBaruNotification extends Notification
{
    use Queueable;

    protected $materi;

    public function __construct(Materi $materi)
    {
        $this->materi = $materi;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'judul' => $this->materi->judul_materi,
            'type' => "Materi",
            'tanggal' => $this->materi->tanggal,
            'tahun_ajaran' => $this->materi->tahun_ajaran,
            'matapelajaran_id' => $this->materi->matapelajaran_id,
        ];
    }
}
