<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fine extends Model
{

    protected $fillable = [
        'amount',
        'status',
        'user_id',
        'library_id',
        'borrow_id',
    ];
    public function borrow()
    {
        return $this->belongsTo(Borrow::class);
    }
}
