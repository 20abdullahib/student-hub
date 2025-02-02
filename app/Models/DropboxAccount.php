<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DropboxAccount extends Model
{
    use HasFactory;
    protected $fillable = [
        'client_id',
        'client_secret',
        'access_token',
        'refresh_token',
        'email',
        'timestamp',
        "token_expires_at",
        'department_id',
        'remaining_storage'
    ];
    protected $casts = [
        'token_expires_at' => 'datetime',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }
}
