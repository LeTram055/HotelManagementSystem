<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'customer_id',
        'message',
        'created_at',
    ];
    protected $primaryKey = 'notification_id';
    public $timestamps = false;
    protected $date = [
        'created_at'
    ];
}