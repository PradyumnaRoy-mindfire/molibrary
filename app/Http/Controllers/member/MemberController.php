<?php

namespace App\Http\Controllers\member;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
// use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\DataTables;


class MemberController extends Controller
{
    //
    public function browseBooks()  {
        $books = Book::with(['author', 'category', 'library'])
            ->select('books.*')
            ->orderBy('created_at', 'desc')
            ->paginate(3);

        $categories = Category::all();

        return view('member.books', compact('books', 'categories'));
    }
    public function showDashboard()  {
        return view('member.dashboard');
    }

            //for search ajax
    public function bookSearch(Request $request){
        $query = $request->input('query');
        
        $books = Book::where('title', 'LIKE', "%{$query}%")
            ->orWhere('isbn', 'LIKE', "%{$query}%")
            ->orWhereHas('author', function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%");
            })
            ->orWhereHas('category', function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%");
            })
            ->with(['author', 'category'])
            ->get()
            ->map(function ($book) {
                return [
                    'id' => $book->id,
                    'title' => $book->title,
                    'isbn' => $book->isbn,
                    'edition' => $book->edition,
                    'published_year' => $book->published_year,
                    'total_copies' => $book->total_copies,
                    'image' => $book->image,
                    'image_url' => $book->image ? asset('storage/' . $book->image) : null,
                    'author_name' => $book->author->name,
                    'category_name' => $book->category->name,
                    'category_slug' => Str::slug($book->category->name)
                ];
            });
        
        return response()->json([
            'books' => $books
        ]);
    }

    public function showMembershipAndPlans(){
        $plans = Plan::all();
        return view('member.membership_plans',compact('plans'));
    }

    public function showBorrowHistory(Request $request, DataTables $dataTables){
        if($request->ajax()){
            $users = User::query();
            return $dataTables->eloquent($users)->make(true);
        }
        return view('member.borrow_history');
    }
    

}
