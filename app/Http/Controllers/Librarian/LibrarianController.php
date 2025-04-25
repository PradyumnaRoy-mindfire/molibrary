<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Borrow;
use App\Models\Librarian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LibrarianController extends Controller
{
    //
    public function bookManagement()
    {
        $libraryId = Librarian::where('user_id', Auth::id())->first()->library_id;
        $borrowRequests = Borrow::with(['user', 'book'])
        ->whereIn('type', ['borrow', 'return'])
        ->where('library_id', $libraryId)
        ->orderBy('created_at', 'desc')
        ->get();
        return view('librarian.book_management', compact('borrowRequests'));
    }

        //accept or reject borrow or return request
    public function processRequest(Request $request, $id)
    {
        $action = $request->input('action'); // 'approve' or 'reject'
        $userRequest = Borrow::findOrFail($id);

        if ($action === 'approve') {
            if ($userRequest->type === 'return') {
                $userRequest->return_date = now();
                $userRequest->status = 'returned';
                
                //update book total_copies
                $userRequest->book->total_copies += 1;
                $userRequest->user->book_limit += 1;
            }else {
                $userRequest->borrow_date = now();
                $userRequest->due_date = now()->addMinutes(120);
                $userRequest->status = 'borrowed';
                $userRequest->book->total_copies -= 1;
            }
        } else if ($action === 'reject') {
            $userRequest->status = 'rejected';
            if ($userRequest->type === 'borrow') {
                $userRequest->user->book_limit += 1;
            }
        }

        $userRequest->save();
        $userRequest->book->save();
        $userRequest->user->save();

        return response()->json([
            'success' => true,
            'message' => ucfirst($action) . ' successful',
            'status' => $userRequest->status === 'borrowed' ? 'approved' : 'rejected'
        ]);
    }
}
