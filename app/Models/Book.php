<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    //
    protected $fillable = [
        'title',
        'author_id',
        'isbn',
        'library_id',
        'edition',
        'category_id',
        'published_year',
        'total_copies',
        'has_ebook',
        'has_paperbook',
        'description',
        'image',
        'ebook_path',
        'preview_content_path',
        'total_copies'
    ];

    // A book belongs to an author
    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    // A book belongs to a category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
        // A book belongs to a library
    public function library()
    {
        return $this->belongsTo(Library::class);
    }
     // A book has many borrows
    public function borrows()
    {
        return $this->hasMany(Borrow::class);
    }
}
