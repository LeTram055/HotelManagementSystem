<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Types extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'types';
    protected $fillable = [
        'type_name',
        'type_price',
        'type_capacity',
        'type_area',
    ];
    protected $guarded = ['type_id'];
    protected $primaryKey = 'type_id';
    public $timestamps = false;

    public function images()
    {
        return $this->hasMany(TypeImages::class, 'type_id', 'type_id');
    }

    public function facilities()
    {
        return $this->belongsToMany(Facilities::class, 'type_facility', 'type_id', 'facility_id');
    }

    public function rooms()
    {
        return $this->hasMany(Rooms::class, 'type_id', 'type_id');
    }

    public function availableRooms()
    {
        return $this->rooms()
            ->whereHas('status', function ($query) {
                $query->where('status_name', 'Trống'); // Kiểm tra tên trạng thái là "Trống"
            })
            ->count();
    }
}