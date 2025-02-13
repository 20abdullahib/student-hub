<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    public $timestamps = true;

    // Define the fillable fields
    protected $fillable = ['name', 'department_id'];

    // Define the relationship with Department
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'branch_subject');
    }
}
