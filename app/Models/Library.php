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

    public function library()
    {
        return $this->belongsTo(Library::class);
    }
}
