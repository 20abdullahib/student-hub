<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DropboxAccountRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'client_id' => 'required|string',
            'client_secret' => 'required|string',
            'refresh_token' => 'required|string',
            'department_id' => 'required|exists:departments,id',
        ];
    }
}