<?php

namespace App\Http\Controllers;

use App\Models\Book;
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
            $rentlogs = RentLogs::with('user', 'book')->paginate(10);
        }


        return view('dashboard', ['book_count' => $bookCount, 'category_count' => $categoryCount, 'user_count' => $userCount, 'rent_logs' => $rentlogs]);
    }
}
