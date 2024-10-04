<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    use HasFactory;
    protected $table = 'payments';
    protected $fillable = [
        'employee_id',
        'reservation_id',
        'payment_date',
        'payment_price',
        'payment_method',
    ];
    protected $guarded = ['payment_id'];
    protected $primaryKey = 'payment_id';
    protected $dates = ['payment_date'];
    protected $dateFormat = 'd-m-Y';
    public $timestamps = false;

    public function employee()
    {
        return $this->belongsTo(Employees::class, 'employee_id', 'employee_id');
    }

    public function reservation()
    {
        return $this->belongsTo(Reservations::class, 'reservation_id', 'reservation_id');
    }
}