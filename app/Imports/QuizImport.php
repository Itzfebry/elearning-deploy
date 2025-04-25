<?php

namespace App\Imports;

use App\Models\QuizQuestions;
use Maatwebsite\Excel\Concerns\ToModel;

class QuizImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new QuizQuestions([
            //
        ]);
    }
}
