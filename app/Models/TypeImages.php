<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TypeImages extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'type_images';
    protected $fillable = [
        'type_id',
        'image_url',
    ];
    protected $guarded = ['image_id'];
    protected $primaryKey = 'image_id';
    public $timestamps = false;

    public function type()
    {
        return $this->belongsTo(Types::class, 'type_id', 'type_id');
    }
}