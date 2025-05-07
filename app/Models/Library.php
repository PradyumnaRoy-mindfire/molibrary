<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Library extends Model
{
    //
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
    public function librarians()
    {
        return $this->hasMany(Librarian::class);
    }

    public function borrows()
    {
        return $this->hasMany(Borrow::class, User::class, 'users_id', 'library_id');
    }

    public function books()
    {
        return $this->hasMany(Book::class)->withTrashed();;
    }

    public function mostBorrowedBooks()
    {
        return $this->books()
            ->withTrashed()
            ->withCount(['borrows' => function ($query) {
                $query->whereIn('type', ['borrow', 'return']);
            }])
            ->having('borrows_count', '>', 1)
            ->orderBy('borrows_count', 'desc');
    }
}
