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
        return [
            'judul' => 'Tugas Baru: ' . $this->tugas->nama,
            'tenggat' => $this->tugas->tenggat,
            'kelas' => $this->tugas->kelas,
            'tahun_ajaran' => $this->tugas->tahun_ajaran,
            'matapelajaran_id' => $this->tugas->matapelajaran_id,
        ];
    }
}
