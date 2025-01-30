<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    // This model will automatically handle timestamps
    public $timestamps = true;

    // Define the fillable fields
    protected $fillable = ['name'];

    /**
     * Define a relationship with the Subject model
     */
    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }
    public function branches()
    {
        return $this->hasMany(Branch::class);
    }

    public function dropboxAccounts()
    {
        return $this->hasMany(DropboxAccount::class);
    }
}
