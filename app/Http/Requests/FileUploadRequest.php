<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileUploadRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'path' => 'required|string',
            'size' => 'required|integer',
            'subject_id' => 'required|exists:subjects,id',
            'dropbox_account_id' => 'required|exists:dropbox_accounts,id',
            'link' => 'required|string',
            'file_id' => 'required|string',
        ];
    }
}