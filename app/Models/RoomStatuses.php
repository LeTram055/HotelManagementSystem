<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomStatuses extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'room_statuses';
    protected $fillable = [
        'status_name',
    ];
    protected $guarded = ['status_id'];
    protected $primaryKey = 'status_id';
    public $timestamps = false;

    public function rooms()
    {
        return $this->hasMany(Rooms::class, 'status_id', 'status_id');
    }
}