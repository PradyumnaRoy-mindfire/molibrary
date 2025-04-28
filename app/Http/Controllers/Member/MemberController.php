<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrow;
use App\Models\Category;
use App\Models\Membership;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;


class MemberController extends Controller
{
    //
    public function browseBooks()  {
        $books = Book::with(['author', 'category', 'library'])
            ->select('books.*')
            ->orderBy('created_at', 'desc')
            ->paginate(4);

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
                    'library'=> $book->library->name,
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
        $memberships = Membership::with('plan')->where('user_id', Auth::user()->id)->get();
        return view('member.membership_plans',compact('plans','memberships'));
    }

    public function showBorrowHistory(Request $request, DataTables $dataTables){
        $userId = Auth::id();
        
        $borrowings = Borrow::with(['book', 'library', 'fine'])
            ->where('users_id', $userId)
            ->whereIn('type', ['borrow', 'return'])
            ->select(
                'borrows.*',
                'borrows.id',
                'borrows.borrow_date as issued_date',
                'borrows.due_date',
                'borrows.return_date',
                'borrows.status',
                'books.title',
                'books.isbn',
                'libraries.name as library'
            )
            ->join('books', 'borrows.book_id', '=', 'books.id')
            ->join('libraries', 'borrows.library_id', '=', 'libraries.id')
            ->leftJoin('fines', 'borrows.id', '=', 'fines.borrow_id')
            ->selectRaw('COALESCE(fines.amount, 0) as fine')
            ->selectRaw('COALESCE(fines.status, "none") as fine_status')
            ->selectRaw('CASE WHEN borrows.return_date IS NOT NULL THEN true ELSE false END as returned')
            ->orderBy('borrows.borrow_date', 'desc')
            ->get();
        
        return view('member.borrow_history', compact('borrowings'));
    
    }
    
    public function returnBook(Borrow $borrow){
        $borrowId = $borrow->update(['type' => 'return','status'=>'pending']);
       
        if($borrowId){
            return response()->json(['success' => true]);
        }else {
            return response()->json(['success' => false, 'message' => 'Failed to return book']);
        }
    }

   
}
