<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Comment;
use App\Models\RentLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RentLogController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->keyword;

        /*$where = '';
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
            ->paginate(10);*/

        /*$rentlogs = RentLogs::with('user', 'book')
            ->where(function ($query) use ($keyword) {
                $query->whereHas('book', function ($query) use ($keyword) {
                    $query->where('title', 'LIKE', '%' . $keyword . '%');
                })
                    ->orWhereHas('user', function ($query) use ($keyword) {
                        $query->where('username', 'LIKE', '%' . $keyword . '%');
                    });
            })
            ->when(Auth::user()->role_id, 2, function ($query) {
                $query->where('lender_id', Auth::user()->id);
            })
            ->when(Auth::user()->role_id, 3, function ($query) {
                $query->where('user_id', Auth::user()->id);
            })
            ->paginate(10);*/

        $rentlogs = RentLogs::with('user', 'book')
            ->where(function ($query) use ($keyword) {
                $query->whereHas('book', function ($query) use ($keyword) {
                    $query->where('title', 'LIKE', '%' . $keyword . '%');
                })
                    ->orWhereHas('user', function ($query) use ($keyword) {
                        $query->where('username', 'LIKE', '%' . $keyword . '%');
                    });
            })
            ->when(Auth::user()->role_id == 2, function ($query) {
                $query->where('lender_id', Auth::user()->id);
            })
            ->when(Auth::user()->role_id == 3, function ($query) {
                $query->where('user_id', Auth::user()->id);
            })
            ->paginate(10);

        if ($rentlogs){
            foreach ($rentlogs as $rent){
                $book = Book::where('id', $rent['book_id'])->first();
                $rent['book_code'] = $book['book_code'];
                $rent['title'] = $book['title'];

                $comment = Comment::where('rent_log_id', $rent['id'])->first();
                if($comment){
                    $rent['comment'] = $comment['comments'];
                }else{
                    $rent['comment'] = '';
                }


            }
        }

        return view('rent_log', ['rent_logs' => $rentlogs]);
    }

    public function bookRequests(Request $request)
    {
        $keyword = $request->keyword;


        $rentlogs = RentLogs::with('user', 'book')
            ->where(function ($query) use ($keyword) {
                $query->whereHas('book', function ($query) use ($keyword) {
                    $query->where('title', 'LIKE', '%' . $keyword . '%');
                })
                    ->orWhereHas('user', function ($query) use ($keyword) {
                        $query->where('username', 'LIKE', '%' . $keyword . '%');
                    });
            })
            ->where('status', '!=', 'Returned')
            /*->where('status', 'Pending')
            ->orWhere('status', 'Accepted')*/
            ->when(Auth::user()->role_id == 2, function ($query) {
                $query->where('lender_id', Auth::user()->id);
            })
            ->when(Auth::user()->role_id == 3, function ($query) {
                $query->where('user_id', Auth::user()->id);
            })
            ->paginate(10);

        if ($rentlogs){
            foreach ($rentlogs as $rent){
                $book = Book::where('id', $rent['book_id'])->first();
                $rent['book_code'] = $book['book_code'];
                $rent['title'] = $book['title'];

                $comment = Comment::where('rent_log_id', $rent['id'])->first();
                if($comment){
                    $rent['comment'] = $comment['comments'];
                }else{
                    $rent['comment'] = '';
                }


            }
        }

        return view('requests', ['rent_logs' => $rentlogs]);
    }

    public function rentedBooks(Request $request)
    {
        $keyword = $request->keyword;

        $rentlogs = RentLogs::with('user', 'book')
            ->where(function ($query) use ($keyword) {
                $query->whereHas('book', function ($query) use ($keyword) {
                    $query->where('title', 'LIKE', '%' . $keyword . '%');
                })
                    ->orWhereHas('user', function ($query) use ($keyword) {
                        $query->where('username', 'LIKE', '%' . $keyword . '%');
                    });
            })
            ->where('status', '!=', 'Returned')
            ->when(Auth::user()->role_id == 2, function ($query) {
                $query->where('lender_id', Auth::user()->id);
            })
            ->when(Auth::user()->role_id == 3, function ($query) {
                $query->where('user_id', Auth::user()->id);
            })
            ->paginate(10);

        if ($rentlogs){
            foreach ($rentlogs as $rent){
                $book = Book::where('id', $rent['book_id'])->first();
                $rent['book_code'] = $book['book_code'];
                $rent['title'] = $book['title'];

                $comment = Comment::where('rent_log_id', $rent['id'])->first();
                if($comment){
                    $rent['comment'] = $comment['comments'];
                }else{
                    $rent['comment'] = '';
                }


            }
        }

        return view('rent_log1', ['rent_logs' => $rentlogs]);
    }

    public function returnBooks(Request $request)
    {
        $keyword = $request->keyword;

        $rentlogs = RentLogs::with('user', 'book')
            ->where(function ($query) use ($keyword) {
                $query->whereHas('book', function ($query) use ($keyword) {
                    $query->where('title', 'LIKE', '%' . $keyword . '%');
                })
                    ->orWhereHas('user', function ($query) use ($keyword) {
                        $query->where('username', 'LIKE', '%' . $keyword . '%');
                    });
            })
            ->where('status', 'Delivered')
            ->when(Auth::user()->role_id == 2, function ($query) {
                $query->where('lender_id', Auth::user()->id);
            })
            ->when(Auth::user()->role_id == 3, function ($query) {
                $query->where('user_id', Auth::user()->id);
            })
            ->paginate(10);

        if ($rentlogs){
            foreach ($rentlogs as $rent){
                $book = Book::where('id', $rent['book_id'])->first();
                $rent['book_code'] = $book['book_code'];
                $rent['title'] = $book['title'];

                $comment = Comment::where('rent_log_id', $rent['id'])->first();
                if($comment){
                    $rent['comment'] = $comment['comments'];
                }else{
                    $rent['comment'] = '';
                }


            }
        }

        return view('rent_log2', ['rent_logs' => $rentlogs]);
    }

}
