<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    use HasFactory;
    protected $table = 'users';
    protected $fillable = [
        'user_username',
        'user_password',
        'user_role',
        'user_active',
        'user_email',
    ];
    protected $guarded = ['user_id'];
    protected $primaryKey = 'user_id';
    public $timestamps = false;

    public function customers()
    {
        return $this->hasMany(Customers::class, 'user_id', 'user_id');
    }

    public function employees()
    {
        return $this->hasMany(Employees::class, 'user_id', 'user_id');
    }
}