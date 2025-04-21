<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'id',
        'type',
        'amount',
        'duration',
        'max_books_limit',
        'ebook_access',
        'priority',
        'visibility',
        'description'
    ];
    
    //
}
