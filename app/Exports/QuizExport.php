<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class QuizExport implements FromView
{
    public function view(): View
    {
        return view('pages.role_guru.quiz.excel_export_quiz');
    }
}
