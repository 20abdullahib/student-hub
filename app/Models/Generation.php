<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Generation extends Model
{
    use HasFactory;


    public $timestamps = true;

    protected $fillable = ['name', 'year_joined', 'branch_id'];

    // Define relationship to Branch
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }


}
