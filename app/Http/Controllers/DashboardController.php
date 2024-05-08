<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Comment;
use App\Models\User;
use App\Models\Category;
use App\Models\RentLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::User()->role_id === 1){
            $bookCount = Book::count();
            $categoryCount = Category::count();
            $userCount = User::count();
            $rentlogs = RentLogs::with('user', 'book')->paginate(10);
        }else{
            $bookCount = Book::where('user_id', Auth::user()->id)->count();
            $categoryCount = Category::count();
            $userCount = User::count();
            $rentlogs = RentLogs::where('lender_id', Auth::user()->id)->with('user', 'book')->paginate(10);
        }

        if ($rentlogs){
            foreach ($rentlogs as $rent){
                $book = Book::where('id', $rent['book_id'])->first();
                if ($book){
                    $rent['book_code'] = $book['book_code'];
                    $rent['title'] = $book['title'];
                }else{
                    $rent['book_code'] = '';
                    $rent['title'] = '';
                }

                $comment = Comment::where('rent_log_id', $rent['id'])->first();
                if($comment){
                    $rent['comment'] = $comment['comments'];
                }else{
                    $rent['comment'] = '';
                }
            }
        }

        return view('dashboard', ['book_count' => $bookCount, 'category_count' => $categoryCount, 'user_count' => $userCount, 'rent_logs' => $rentlogs]);
    }
}
