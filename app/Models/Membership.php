<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    //
    protected $fillable = [  
        'id',	
        'payments_method_id',
        'has_access',
        'user_id',
        'plan_id',
        'start_date',
        'end_date',
        'renewed_at',
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
