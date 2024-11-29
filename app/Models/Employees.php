<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employees extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'employees';
    protected $fillable = [
        'employee_name',
        'employee_phone',
        'employee_address',
        'employee_status',
        'employee_email',
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