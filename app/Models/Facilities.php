<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Facilities extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'facilities';
    protected $fillable = [
        'facility_name',
        'facility_description',
    ];
    protected $guarded = ['facility_id'];
    protected $primaryKey = 'facility_id';
    public $timestamps = false;

    public function types()
    {
        return $this->belongsToMany(Types::class, 'type_facility', 'facility_id', 'type_id');
    }
}