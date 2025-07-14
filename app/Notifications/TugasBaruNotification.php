<?php

namespace App\Notifications;

use App\Models\Tugas;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TugasBaruNotification extends Notification
{
    use Queueable;

    protected $tugas;

    public function __construct(Tugas $tugas)
    {
        $this->tugas = $tugas;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        // Pastikan relasi sudah di-load
        $this->tugas->loadMissing('mataPelajaran');
        return [
            'judul' => $this->tugas->nama,
            'type' => "Tugas",
            'matapelajaran_id' => $this->tugas->matapelajaran_id,
            'matapelajaran_nama' => $this->tugas->mataPelajaran->nama ?? null,
        ];
    }
}
