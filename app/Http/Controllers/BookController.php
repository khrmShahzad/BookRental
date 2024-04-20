<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Comment;
use App\Models\RentLogs;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->keyword;

        /*$where = '';
        if (Auth::user()->role_id == 2){
            $where = 'user_id = '.Auth::user()->id;
        }

        $books = Book::with('categories')
            ->when($where, function ($query, $where) {
                return $query->whereRaw($where);
            })
            ->where('book_code', 'LIKE', '%' . $keyword . '%')
            ->orWhere('title', 'LIKE', '%' . $keyword . '%')
            ->orWhere('status', 'LIKE', '%' . $keyword . '%')
            ->orWhereHas('categories', function ($query) use ($keyword) {
                $query->where('name', 'LIKE', '%' . $keyword . '%');
            })
            ->paginate(10);*/
        $books = Book::with('categories')
            ->where(function ($query) use ($keyword) {
                $query->where('book_code', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('title', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('status', 'LIKE', '%' . $keyword . '%')
                    ->orWhereHas('categories', function ($query) use ($keyword) {
                        $query->where('name', 'LIKE', '%' . $keyword . '%');
                    });
            })
            ->when(Auth::user()->role_id == 2, function ($query) {
                $query->where('user_id', Auth::user()->id);
            })
            ->paginate(10);
        return view('book', ['books' => $books]);
    }

    public function add()
    {
        $categories = Category::all();
        return view('book-add', ['categories' => $categories]);
    }

    public function store(Request $request)
    {
        // validation
        $validated = $request->validate([
            //'book_code' => 'required|unique:books|max:255',
            'title' => 'required|max:255',
            'author' => 'required|max:255',
            'description' => 'required|max:255',
            'charges' => 'required',
            'total_copies' => 'required',
            'available_copies' => 'required'
        ]);

        $newName = '';
        if ($request->file('image')) {
            $extension = $request->file('image')->getClientOriginalExtension();
            $newName = $request->title . '-' . now()->timestamp . '.' . $extension;
//            $request->file('image')->storeAs('cover', $newName);
            $request->file('image')->move(public_path('cover'), $newName);
        }

        $request['cover'] = $newName;
        $request->user_id = Auth::user()->id;
        $request->merge(['user_id' => Auth::user()->id]);

        // Get the latest book code from the database
        $latestBook = Book::orderBy('id', 'desc')->first();
        $lastBookCode = $latestBook ? substr($latestBook->book_code, 3) : 0;
        // Increment the last book code and format it with leading zeros
        $nextBookCode = 'bk-' . str_pad($lastBookCode + 1, 4, '0', STR_PAD_LEFT);

        $request->merge(['book_code' => $nextBookCode]);

        $book = Book::create($request->all());
        $book->categories()->sync($request->categories);
        return redirect('books')->with('status', 'Book Added Successfully!');
    }

    public function edit($slug)
    {
        $book = Book::where('slug', $slug)->first();
        $categories = Category::all();
        return view('book-edit', ['book' => $book, 'categories' => $categories]);
    }

    public function update(Request $request, $slug)
    {
        $newName = '';

        if ($request->file('image')) {
            $extension = $request->file('image')->getClientOriginalExtension();
            $newName = $request->title . '-' . now()->timestamp . '.' . $extension;
//            $request->file('image')->storeAs('cover', $newName);
            $request->file('image')->move(public_path('cover'), $newName);
            $request['cover'] = $newName;
        }

        $book = Book::where('slug', $slug)->first();


        if ($book->cover != '' && file_exists(public_path('cover/'. $book->cover))){
            unlink(public_path('cover/'. $book->cover));
        }

        //Storage::disk('public')->delete('cover/' . $book->cover);

        $book->update($request->all());

        if ($request->categories) {
            $book->categories()->sync($request->categories);
        }

        return redirect('books')->with('status', 'Book Updated Successfully!');
    }

    public function delete($slug)
    {
        $book = Book::where('slug', $slug)->first();
        return view('book-delete', ['book' => $book]);
    }

    public function destroy($slug)
    {
        $book = Book::where('slug', $slug)->first();

        if ($book->cover != '' && file_exists(public_path('cover/'. $book->cover))){
            unlink(public_path('cover/'. $book->cover));
        }

        $book->delete();
        return redirect('books')->with('status', 'Book Deleted Successfully!');
    }

    public function deletedBook()
    {
        $deletedBooks = Book::onlyTrashed()->get();
        return view('book-deleted-list', ['deletedBooks' => $deletedBooks]);
    }

    public function restore($slug)
    {
        $book = Book::withTrashed()->where('slug', $slug)->first();
        $book->restore();
        return redirect('books')->with('status', 'Book Restored Successfully!');
    }

    public function permanentDelete($slug)
    {
        $deletedBook = Book::withTrashed()->where('slug', $slug)->first();
        $checkRentLogs = RentLogs::where('book_id',$deletedBook->id)->first();
        if ($checkRentLogs){

            Session::flash('status', 'success');
            Session::flash('message', "You cannot Delete Permanent Book data $deletedBook->name because it has rent logs details");

        }else{

            // Menghapus data terkait di tabel anak
            $deletedBook->bookCategories()->delete();

            Storage::disk('public')->delete('cover/' . $deletedBook->cover);
            $deletedBook->forceDelete();

            if ($deletedBook) {
                Session::flash('status', 'success');
                Session::flash('message', "Delete Permanent Book data $deletedBook->name successfully");
            }
        }



        return redirect('/books');
    }

    public function details($id)
    {
        $book = Book::find($id);
        if ($book){
            $book['comments'] = Comment::where('book_id', $book['id'])->get();
            if ($book['comments']){
                foreach ($book['comments'] as $comment){
                    $rentLogDetails = RentLogs::find($comment['rent_log_id']);
                    $rentLogDetails['user'] = User::find($rentLogDetails['user_id']);

                    $comment['rentLogDetails'] = $rentLogDetails;

                }
            }
        }
        $categories = Category::all();
        return view('book-detail', ['book' => $book, 'categories' => $categories]);
    }

    public function accept(){
        $updated = RentLogs::where('status', 'Pending')->update(['status' => 'Accepted']);

        return redirect()->back()->with('status', 'Status has been updated!');
    }
}
