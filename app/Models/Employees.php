<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{
    use HasFactory;
    protected $table = 'employees';
    protected $fillable = [
        'employee_name',
        'employee_phone',
        'employee_address',
        'employee_status',
        'account_id',
    ];
    protected $guarded = ['employee_id'];
    protected $primaryKey = 'employee_id';
    public $timestamps = false;

    public function account()
    {
        return $this->belongsTo(Accounts::class, 'account_id', 'account_id');
    }

    public function payments()
    {
        return $this->hasMany(Payments::class, 'employee_id', 'employee_id');
    }
}