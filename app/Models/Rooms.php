<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rooms extends Model
{
    use HasFactory;
    protected $table = 'rooms';
    protected $fillable = [
        'type_id',
        'status_id',
        'room_name',
        'room_note',
    ];
    protected $guarded = ['room_id'];
    protected $primaryKey = 'room_id';
    public $timestamps = false;

    public function type()
    {
        return $this->belongsTo(Types::class, 'type_id', 'type_id');
    }

    public function status()
    {
        return $this->belongsTo(RoomStatuses::class, 'status_id', 'status_id');
    }

    public function reservations()
    {
        return $this->belongsToMany(Reservations::class, 'room_reservation', 'room_id', 'reservation_id');
    }
}