<?php

namespace App\Http\Controllers\library_admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GenreRequest;
use App\Models\Borrow;
use App\Models\Category;

class ManageGenreController extends Controller
{
    //
    public function showGenres()
    {
        $totalGlobalBorrowed = Borrow::count();

        $categories = Category::with(['books'])->get()->map(function ($category) use ($totalGlobalBorrowed) {
            $totalAvailableBooks = $category->books->sum('total_copies');

            $borrowedCount = Borrow::whereIn('book_id', $category->books->pluck('id'))->count();

            $borrowSharePercent = $totalGlobalBorrowed > 0
                ? round(($borrowedCount / $totalGlobalBorrowed) * 100, 2)
                : 0;

            return [
                'category_id' => $category->id,
                'name' => $category->name,
                'description' => $category->description,
                'available_books' => $totalAvailableBooks,
                'borrowed_books' => $borrowedCount,
                'preferencePercentage' => $borrowSharePercent,
            ];
        });
        return view('library_admin.manage_genres',compact('categories'));
    }

    public function addGenre() {
        return view('library_admin.add_genre');
    }

    public function storeGenre(GenreRequest $request) {
        $validated = $request->validated();

        $result = Category::create($validated);

        if (!$result) {
            return back()->with('genreStored', 'Genre not added Successfully..');
        }

        return redirect()->route('manage.genres')->with('genreStored', 'Genre added Successfully..');
    }
    
    public function updateGenre(GenreRequest $request, Category $category)
    {
        $validated = $request->validated();

        $result = $category->update($validated);

        return response()->json(['success' => true, 'message' => 'Genre updated successfully,',$validated]);
    }
}
