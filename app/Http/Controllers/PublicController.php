<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCategory;
use App\Models\Category;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();

        if ($request->category || $request->title) {
            $books = Book::where('title', 'LIKE', '%' . $request->title . '%')->orWhereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category);
            })->get();
        } else {
            $books = Book::all();
        }

        if ($books){
            foreach ($books as $book){
                $categoryDetails = BookCategory::select('category_id')->where('book_id', $book['id'])->first();
                $book['category_id'] = $categoryDetails['category_id'];
            }
        }

        $recent_books = Book::orderBy('created_at', 'desc')->take(3)->get();

        return view('book-list', ['books' => $books, 'categories' => $categories, 'recent_books' => $recent_books]);
    }
}
