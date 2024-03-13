<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\RentLogs;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
//use Stripe\Stripe;
use Stripe;

class BorrowController extends Controller
{
    public function index(Request $request, $id)
    {
        $book = Book::find($id);
        return view('borrow', ['book' => $book]);
    }

    public function checkout(Request $request)
    {


//        $request['rent_date'] = Carbon::now()->toDateString();
//        $request['return_date'] = Carbon::now()->addDay(7)->toDateString();

//        dd($request->book_id);

        $book = Book::findOrFail($request->book_id)->only('status');

        // if book = not available
        if ($book['status'] != 'in stock') {
            Session::flash('message', "Can't rent, the book is not available");
            Session::flash('alert-class', "alert-danger");
            return back();
            //return redirect('borrow-req/'.$request->book_id);
        } else {
            $count = RentLogs::where('user_id', $request->user_id)->where('actual_return_date', null)->count();

            if ($count >= 3) {

                Session::flash('message', "Can't rent, user has reach limit of books");
                Session::flash('alert-class', "alert-danger");

                return back();
//                return redirect('/');

            } else {

                try {

                    DB::beginTransaction();

                    // process insert to rent_logs table
                    RentLogs::create($request->all());

                    // process update book table
                    $book = Book::findOrFail($request->book_id);
                    $book->status = 'not available';
                    $book->save();
                    DB::commit();

                    Stripe\Stripe::setApiKey('sk_test_51IyFaOFkT1UraQaB6kV44FKopRG6xRNx9yUqFj6UPkqkgLCEZX9O7lee5nUqbvY0atahtCmTTleP6jpHsQugjXdI00bRwEbgca');

                    $session = Stripe\Charge::create ([
                        "amount" => 100 * $request->charges,
                        "currency" => "usd",
                        "source" => $request->stripeToken,
                        "description" => "Book Code ".$request->book_code." has been order by ".Auth::user()->username
                    ]);

                    if ($session){
                        Session::flash('status', 'success');
                        Session::flash('message', "Book has been borrowed successfully");
                    }else{
                        Session::flash('message', "Can't rent, something went wrong");
                        Session::flash('alert-class', "alert-danger");
                    }

                    return back();




                } catch (\Throwable $th) {
                    DB::rollBack();
                }
            }
        }




    }


}
