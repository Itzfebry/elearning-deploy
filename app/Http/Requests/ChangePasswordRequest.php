<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    public function rules()
    {
        return [
            'old_password' => ['required'],
            'new_password' => ['required', 'min:8', 'confirmed'],
        ];
    }

}
