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
        'user_id',
    ];
    protected $guarded = ['employee_id'];
    protected $primaryKey = 'employee_id';
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id', 'user_id');
    }

    public function payments()
    {
        return $this->hasMany(Payments::class, 'employee_id', 'employee_id');
    }
}