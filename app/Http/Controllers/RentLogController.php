<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\RentLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RentLogController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->keyword;

        $where = '';
        if (Auth::user()->role_id == 2 || Auth::user()->role_id == 3){
            $where = 'user_id = '.Auth::user()->id;
        }

        $rentlogs = RentLogs::with('user', 'book')
            ->when($where, function ($query, $where) {
                return $query->whereRaw($where);
            })
            ->whereHas('book', function ($query) use ($keyword) {
                $query->where('title', 'LIKE', '%' . $keyword . '%');
            })
            ->orWhereHas('user', function ($query) use ($keyword) {
                $query->where('username', 'LIKE', '%' . $keyword . '%');
            })
            ->paginate(10);

        foreach ($rentlogs as $rent){
            $book = Book::where('id', $rent['book_id'])->first();
            $rent['book_code'] = $book['book_code'];
            $rent['title'] = $book['title'];
        }

        return view('rent_log', ['rent_logs' => $rentlogs]);
    }
}
