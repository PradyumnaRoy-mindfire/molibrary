<?php

namespace App\Http\Controllers\LibraryAdmin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Librarian;
use App\Models\Library;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LibraryAdminController extends Controller
{
    //
    public function showDashboard()
    {
        return view('library_admin.dashboard');
    }

    public function showLibrarybooks()
    {
        $adminId = Auth::id();
        $books = Book::with(['author', 'category', 'library'])
            ->whereHas('library', function ($query) use ($adminId) {
                $query->where('admin_id', $adminId);
            })
            ->select('books.*')
            ->orderBy('created_at', 'desc')
            ->paginate(4);

        $categories = Category::all();

        return view('library_admin.manage_books', compact('books', 'categories'));
    }

    public function approveLibrarians()
    {

        $library = Library::where('admin_id', Auth::id())->first();

        if (!$library) {
            return response()->json(['error' => 'Library not found for this admin.'], 404);
        }

        $librarians = User::where('role', 'librarian')
            ->whereHas('librarian', function ($query) use ($library) {
                $query->where('library_id', $library->id);
                //   ->where('status', 'pending');
            })
            ->with(['librarian' => function ($query) use ($library) {
                $query->where('library_id', $library->id);
                // ->where('status', 'pending');
            }])
            ->select(['id', 'name', 'email', 'created_at'])->get();
        return view('library_admin.approve_librarians', compact('librarians'));
    }

    public function updateStatus($id, $action)
    {
        try {
            $librarian = Librarian::findOrFail($id);

            if ($librarian->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'This librarian has already been ' . $librarian->status
                ]);
            }

            // Updating the status based on action
            $status = $action === 'accept' ? 'approved' : 'rejected';
            $librarian->status = $status;
            
            $librarian->save();


            return response()->json([
                'success' => true,
                'message' => 'Librarian has been ' . $status . ' successfully',
                'status' => $status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating librarian status: ' . $e->getMessage()
            ], 500);
        }
    }
}
