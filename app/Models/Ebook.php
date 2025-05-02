<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ebook extends Model
{
    //
    protected $fillable = [
        'user_id',
        'book_id',
        'reading_progress',
        'ebook_path',
    ];
     // user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // book
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
