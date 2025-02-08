<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'description',
        'department_id',
        'code'
    ];

    /**
     * Define a relationship with the Department model
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function branches()
    {
        return $this->belongsToMany(Branch::class, 'branch_subject');
    }

}
