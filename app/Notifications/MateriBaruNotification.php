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
        $this->materi->loadMissing('mataPelajaran');
        return [
            'judul' => $this->materi->judul_materi,
            'type' => "Materi",
            'matapelajaran_id' => $this->materi->matapelajaran_id,
            'matapelajaran_nama' => $this->materi->mataPelajaran->nama ?? null,
        ];
    }
}
