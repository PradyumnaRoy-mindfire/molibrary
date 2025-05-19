<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Ebook;
use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;
class EbookController extends Controller
{
    //

    public function readEbook(Book $book)
    {   
        //to count the number of pages
        $path = storage_path('app/public/' . $book->ebook_path);
        $parser = new Parser();
        $pdf = $parser->parseFile($path);
        $details = $pdf->getDetails();
            //to show the starting page
        $pageCount = $details['Pages'] ?? null;

        $readingProgress = auth()->user()->ebooks()->where('book_id', $book->id)->first();

        $startPage = $readingProgress ? ($readingProgress->reading_progress /100)* $pageCount : 1;
        $startPage = (int) $startPage;

        return view('member.read_ebook', compact('book','startPage'));
    }

    public function ebookReadingProgress(Book $book, Request $request) {
        $user = auth()->user(); 

        $ebook = Ebook::updateOrCreate(
            [
                'user_id' => $user->id,     //this section is for searching the ebook
                'book_id' => $book->id,
            ],
            [
                'reading_progress' => $request->progress,  //this section is for updating the progress of the ebook
            ]
        );
        
        return response()->json(['success' => true, 'progress' => $request->progress]);
    }
}
