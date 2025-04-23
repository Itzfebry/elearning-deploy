<?php

namespace App\Imports;

use App\Models\QuizQuestion;
use Maatwebsite\Excel\Concerns\ToModel;

class QuizQuestionsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new QuizQuestion([
            //
        ]);
    }
}
