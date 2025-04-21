<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    //
    protected $fillable = [
        'book_id',
        'library_id',
        'users_id',
        'type',
        'status',
        'borrow_date',
        'due_date',
        'return_date',
    ];
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
    public function fine()
    {
        return $this->hasOne(Fine::class, 'borrow_id');
    }
}
