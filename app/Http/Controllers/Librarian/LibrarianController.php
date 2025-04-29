<?php

namespace App\Http\Controllers\Librarian;

use App\Http\Controllers\Controller;
use App\Models\Borrow;
use App\Models\Fine;
use App\Models\Librarian;
use App\Notifications\BorrowRequestApprovedNotification;
use App\Notifications\FineNotification;
use Carbon\Carbon;
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

                //IMpose fine
                $returnDate = Carbon::parse($userRequest->return_date);
                $dueDate = Carbon::parse($userRequest->due_date);
                if ($returnDate->gt($dueDate)) {
                    $minutesLate = $dueDate->diffInMinutes($returnDate);

                    $finePerMinute = 1;     //fine amount is 1 rupees per minute
                    $fineAmount = round($minutesLate * $finePerMinute, 2);
                    $userRequest->fine()->create([
                        'amount' => $fineAmount,
                        'status' => 'pending',
                    ]);

                    //sending mail to the user about fine
                    $userRequest->user->notify(new FineNotification($userRequest));
                }

                //update book total_copies
                $userRequest->book->total_copies += 1;
                $userRequest->user->book_limit += 1;
            } else {
                $userRequest->borrow_date = now();
                $userRequest->due_date = now()->addMinutes(2);
                $userRequest->status = 'borrowed';
                $userRequest->book->total_copies -= 1;

                //sending mail to the user about borrow request
                $userRequest->user->notify(new BorrowRequestApprovedNotification($userRequest)); 
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
