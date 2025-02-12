<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',          
        'path',
        'link',
        'file_id',          
        'size',
        'rlkey',          
        'subject_id',    
        'dropbox_account_id', 
        'created_at',    
        'updated_at',    
    ];

    public function subject() {
        return $this->belongsTo(Subject::class);
    }
    
    public function dropboxAccount() {
        return $this->belongsTo(DropboxAccount::class);
    }
}
