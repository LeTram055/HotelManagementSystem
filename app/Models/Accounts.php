<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accounts extends Model
{
    use HasFactory;
    protected $table = 'accounts';
    protected $fillable = [
        'account_username',
        'account_password',
        'account_role',
        'account_active',
    ];
    protected $guarded = ['account_id'];
    protected $primaryKey = 'account_id';
    public $timestamps = false;

    public function customers()
    {
        return $this->hasMany(Customers::class, 'account_id', 'account_id');
    }

    public function employees()
    {
        return $this->hasMany(Employees::class, 'account_id', 'account_id');
    }
}