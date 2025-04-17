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

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function library()
    {
        return $this->belongsTo(Library::class);
    }
}
