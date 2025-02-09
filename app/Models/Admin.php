<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasFactory;
    use HasRoles;
    
    protected $guard_name = 'admin';

    protected $fillable = [
        'name',
        'email',
        'password',
        'department_id',
        'branch_id',
        'role',
    ];

    
    protected $hidden = [
        'password', 'remember_token'
    ];

    // Relationships
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

 

}