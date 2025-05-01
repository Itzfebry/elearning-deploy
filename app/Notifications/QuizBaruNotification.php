<?php

namespace App\Notifications;

use App\Models\Quizzes;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class QuizBaruNotification extends Notification
{
    use Queueable;

    protected $param;

    public function __construct(Quizzes $quizzes)
    {
        $this->param = $quizzes;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'judul' => $this->param->judul,
            'type' => "Quiz",
            'matapelajaran_id' => $this->param->matapelajaran_id,
        ];
    }
}
