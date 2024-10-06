<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservations extends Model
{
    use HasFactory;
    protected $table = 'reservations';
    protected $fillable = [
        'customer_id',
        'reservation_date',
        'reservation_checkin',
        'reservation_checkout',
        'reservation_status',
    ];
    protected $guarded = ['reservation_id'];
    protected $primaryKey = 'reservation_id';
    protected $dates = [
        'reservation_date',
        'reservation_checkin',
        'reservation_checkout',
    ];
    public $timestamps = false;

    public function customer()
    {
        return $this->belongsTo(Customers::class, 'customer_id', 'customer_id');
    }

    public function rooms()
    {
        return $this->belongsToMany(Rooms::class, 'room_reservation', 'reservation_id', 'room_id');
    }

    public function payments()
    {
        return $this->hasOne(Payments::class, 'reservation_id', 'reservation_id');
    }

    public function roomReservations()
    {
        return $this->hasMany(RoomReservation::class, 'reservation_id');
    }
}