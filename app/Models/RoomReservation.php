<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomReservation extends Model
{
    use HasFactory;
    protected $table = 'room_reservation';
    protected $fillable = ['reservation_id', 'room_id'];
    public $timestamps = false;

    public function reservation()
    {
        return $this->belongsTo(Reservations::class, 'reservation_id');
    }

    
    public function room()
    {
        return $this->belongsTo(Rooms::class, 'room_id');
    }
}