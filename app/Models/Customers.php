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
        'account_id',
    ];
    protected $guarded = ['customer_id'];
    protected $primaryKey = 'customer_id';
    public $timestamps = false;

    public function account()
    {
        return $this->belongsTo(Accounts::class, 'account_id', 'account_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservations::class, 'customer_id', 'customer_id');
    }
}