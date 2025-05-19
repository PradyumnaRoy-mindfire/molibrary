<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //
    protected $fillable = [
        'payment_method_id',
        'type',
        'user_id', 
        'method',
        'amount',
        'status'
    ];
}
