<?php

namespace App\Http\Controllers\LibraryAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookRequest;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Library;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{

    public function addBookForm(Library $library)
    {
        $authors = Author::all();
        $categories = Category::all();
        return view('library_admin.add_edit_book', compact('authors', 'categories','library'));
    }

    public function storeBook(BookRequest $request, Library $library)
    {
        $request->validated();
    
        // Handle author - either existing or new
        if ($request->author_id === 'new-author' && $request->filled('new_author_name')) {
            // Creating new author
            $author = Author::create([
                'name' => $request->new_author_name
            ]);
            $authorId = $author->id;
        } else {
            $authorId = $request->author_id;
        }
    
        // Handling file uploads
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('book_covers', 'public');
        }
    
        $ebookPath = null;
        if ($request->hasFile('ebook_path') && $request->has_ebook) {
            $ebookPath = $request->file('ebook_path')->store('ebooks', 'public');
        }
    
        $previewPath = null;
        if ($request->hasFile('preview_content_path')) {
            $previewPath = $request->file('preview_content_path')->store('book_previews', 'public');
        }
    
        // Creatiing a book  new record
        $book = Book::create([
            'title' => $request->title,
            'author_id' => $authorId,
            'isbn' => $request->isbn,
            'library_id' => $library->id,
            'edition' => $request->edition,
            'category_id' => $request->category_id,
            'published_year' => $request->published_year,
            'total_copies' => $request->total_copies,
            'has_ebook' => $request->has_ebook,
            'has_paperbook' => $request->has_paperbook,
            'description' => $request->description,
            'image' => $imagePath,
            'ebook_path' => $ebookPath,
            'preview_content_path' => $previewPath,
            'total_copies' => $request->total_copies, 
        ]);
    
        if($book) {
            return redirect()->route('manage.books')->with('bookStoreSuccess', 'Book added successfully!');
        } else {
            return redirect()->back()->with('bookStoreError', 'Something went wrong!');
        }

    }

    public function editBookForm(Book $book)
    {   
        $authors = Author::all();
        $categories = Category::all();
        return view('library_admin.add_edit_book', compact('book', 'authors', 'categories'));
    }

    public function editBook(BookRequest $request, Book $book)
    {
        $validated = $request->validated();
        
        // for author
        if ($request->author_id === 'new-author' && $request->filled('new_author_name')) {
            $author = Author::create([
                'name' => $request->new_author_name
            ]);
            $authorId = $author->id;
        } else {
            $authorId = $request->author_id;
        }
        
        $imagePath = $book->image; // Keep existing path 
        if ($request->hasFile('image')) {
            // Delete old file if exists
            if ($book->image && Storage::disk('public')->exists($book->image)) {
                Storage::disk('public')->delete($book->image);
            }
            $imagePath = $request->file('image')->store('book_covers', 'public');
        }
        
        $ebookPath = $book->ebook_path; 
        if ($request->hasFile('ebook_path') && $request->has_ebook) {
            if ($book->ebook_path && Storage::disk('public')->exists($book->ebook_path)) {
                Storage::disk('public')->delete($book->ebook_path);
            }
            $ebookPath = $request->file('ebook_path')->store('ebooks', 'public');
        }
        
        $previewPath = $book->preview_content_path; 
        if ($request->hasFile('preview_content_path')) {
            if ($book->preview_content_path && Storage::disk('public')->exists($book->preview_content_path)) {
                Storage::disk('public')->delete($book->preview_content_path);
            }
            $previewPath = $request->file('preview_content_path')->store('book_previews', 'public');
        }
        
        $book->update([
            'title' => $request->title,
            'author_id' => $authorId,
            'isbn' => $request->isbn,
            'edition' => $request->edition,
            'category_id' => $request->category_id,
            'published_year' => $request->published_year,
            'total_copies' => $request->total_copies,
            'has_ebook' => $request->has_ebook,
            'has_paperbook' => $request->has_paperbook,
            'description' => $request->description,
            'image' => $imagePath,
            'ebook_path' => $ebookPath,
            'preview_content_path' => $previewPath,
        ]);
        
        return redirect()->route('manage.books')->with('bookUpdate', 'Book updated Successfully!!');
    }

    public function deleteBook(Book $book)
    {
        $book->delete();
        return redirect()->back()->with('bookDelete', 'Book deleted Successfully!!');
    }
}
