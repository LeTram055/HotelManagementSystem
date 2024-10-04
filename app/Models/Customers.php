<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    use HasFactory;
    protected $table = 'customers';
    protected $fillable = [
        'customer_name',
        'customer_phone',
        'customer_address',
        'user_id',
    ];
    protected $guarded = ['customer_id'];
    protected $primaryKey = 'customer_id';
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id', 'user_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservations::class, 'customer_id', 'customer_id');
    }
}