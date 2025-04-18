<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    //
     // A borrow belongs to a book
     public function book()
     {
         return $this->belongsTo(Book::class);
     }
 
     // A borrow belongs to a user
     public function user()
     {
         return $this->belongsTo(User::class);
     }
}
