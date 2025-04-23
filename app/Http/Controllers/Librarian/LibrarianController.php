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

    public function processRequest(Request $request, $id)
    {
        $action = $request->input('action'); // 'approve' or 'reject'
        $borrowRequest = Borrow::findOrFail($id);

        if ($action === 'approve') {
            $borrowRequest->status = 'borrowed';
        } else if ($action === 'reject') {
            $borrowRequest->status = 'rejected';
        }

        $borrowRequest->save();

        return response()->json([
            'success' => true,
            'message' => ucfirst($action) . ' successful',
            'status' => $borrowRequest->status === 'borrowed' ? 'approved' : 'rejected'
        ]);
    }
}
