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

        $security = 0;
        $request['rent_date'] = Carbon::now()->toDateString();
        $request['return_date'] = Carbon::now()->addDay(7)->toDateString();
        $request['user_id'] = Auth::user()->id;

//        $book = Book::findOrFail($request->book_id)->only('status');
        $book = Book::where('book_code',$request->book_code)->first();//only('status');

        $request->merge(['book_id' => $book->id]);
        // if book = not available
        if ($book['available_copies'] != 0) {
            Session::flash('status', "Can't rent, the book is not available");
            return back();
        } else {
            $count = RentLogs::where('user_id', $request->user_id)->where('actual_return_date', null)->count();

            if ($count >= 3) {

                Session::flash('status', "Can't rent, user has reach limit of books");

                return back();

            } else {



                // process update book table
                $book = Book::findOrFail($request->book_id);
                $security = $book->security;
                $book->status = 'not available';
                $book->available_copies = $book->available_copies - $request->copies;
                $book->save();

                $request->merge(['security_submitted' => $security]);
                $request->merge(['security_returned' => 0]);
                $request->merge(['status' => 'pending']);
                $request->merge(['lender_id' => $book->user_id]);
                // process insert to rent_logs table
                RentLogs::create($request->all());


                Stripe\Stripe::setApiKey('sk_test_51OuCTPDVHG7hkxkWNKqQstjHSt07DTdHAq87kTdWZEj1OYOdvEr0mlHLfz1o1JyBJQOhwgct5oNr7OUinMBCb7VQ00UEJsmQeV');

                $session = Stripe\Charge::create ([
                    "amount" => 100 * $request->charges,
                    "currency" => "usd",
                    "source" => $request->stripeToken,
                    "description" => "Book Code ".$request->book_code." has been order by ".Auth::user()->username
                ]);

                if ($session){
                    Session::flash('status', "Payment completed successfully, you order is in pending process...");
                }else{
                    Session::flash('status', "Can't rent, something went wrong");
                }

                return back();

            }
        }

    }

}
