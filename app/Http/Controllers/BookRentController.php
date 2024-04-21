<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Rating;
use Carbon\Carbon;
use App\Models\Book;
use App\Models\User;
use App\Models\RentLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Stripe;

class BookRentController extends Controller
{
    public function index()
    {
        $users = User::where([
            ['role_id', '!=', 1],
            ['status', '!=', 'inactive']
        ])->get();
        $books = Book::all();
        return view('rent-book', ['users' => $users, 'books' => $books]);
    }

    public function store(Request $request)
    {
        $request['rent_date'] = Carbon::now()->toDateString();
        $request['return_date'] = Carbon::now()->addDay(7)->toDateString();

        $book = Book::findOrFail($request->book_id)->only('status');

        // if book = not available
        if ($book['status'] != 'in stock') {
            Session::flash('message', "Can't rent, the book is not available");
            Session::flash('alert-class', "alert-danger");
            return redirect('book-rent');
        } else {
            $count = RentLogs::where('user_id', $request->user_id)->where('actual_return_date', null)->count();

            if ($count >= 3) {
                Session::flash('message', "Can't rent, user has reach limit of books");
                Session::flash('alert-class', "alert-danger");
                return redirect('book-rent');
            } else {
                try {
                    // Database Transaction karena lebih dari 1 proses
                    DB::beginTransaction();

                    // process insert to rent_logs table
                    RentLogs::create($request->all());

                    // process update book table
                    $book = Book::findOrFail($request->book_id);
                    $book->status = 'not available';
                    $book->save();
                    DB::commit();

                    Session::flash('message', "Rent Book Success");
                    Session::flash('alert-class', "alert-success");
                    return redirect('book-rent');
                } catch (\Throwable $th) {
                    DB::rollBack();
                }
            }
        }
    }

    public function returnBook()
    {
        $users = User::where([
            ['role_id', '!=', 1],
            ['status', '!=', 'inactive']
        ])->get();

        return view('return-book', ['users' => $users]);
    }

    public function getUserBooks($user_id)
    {
        $books = Book::whereHas('rentLogs', function ($query) use ($user_id) {
            $query->where([
                ['user_id', $user_id],
                ['actual_return_date', null]
            ]);
        })->get();

        return response()->json($books);
    }


    public function saveReturnBook(Request $request)
    {
        $rent = RentLogs::where([
            ['user_id', $request->user_id],
            ['book_id', $request->book_id],
            ['actual_return_date', '=', NULL]
        ]);
        $rentData = $rent->first();
        $countData = $rent->count();

        if ($countData === 1) {
            try {
                DB::beginTransaction();

                $rentData->actual_return_date = Carbon::now()->toDateString();
                $rentData->save();

                $book = Book::findOrFail($request->book_id);
                $book->status = 'in stock';
                $book->save();

                DB::commit();

                Session::flash('message', "Book is returned successfully");
                Session::flash('alert-class', "alert-success");
                return redirect('book-return');
            } catch (\Throwable $th) {
                DB::rollBack();
            }
        } else {
            Session::flash('message', "Error in returning the book!");
            Session::flash('alert-class', "alert-danger");
            return redirect('book-return');
        }
    }

    public function rateBook(Request $request)
    {
        $star = $request->star;
        $id = $request->id;
        $isExists = RentLogs::find($id);

        if ($isExists) {
            $isExists->rating = $star;
            $isExists->save();
        }
        /*else {
            $data = Rating::create([
                'user_id' => Auth::user()->id,
                'book_id' => $id,
                'rating' => $star
            ]);
        }*/

        $book = Book::find($isExists->book_id);
        $averageRating = RentLogs::where('id', $id)->avg('rating');

        $book->overall_rating = $averageRating;
        $book->save();

        Session::flash('message', "Book has been rated");
        Session::flash('alert-class', "alert-success");

        $responseData = [
            'message' => 'Rating has been updated',
            'status' => 'success'
        ];

        return response()->json($responseData);

    }

    public function returnBookNew(Request $request)
    {
        $id = $request->id;
        $book_id = $request->book_id;
        $comment = $request->comment;
        $flag = $request->flag;
        $copies = 1;

        if($flag == 0){
            $rentData = RentLogs::find($id);
            $rentData->actual_return_date = Carbon::now()->toDateString();
            $rentData->status = 'Returned';
            $copies = $rentData->copies;
            $rentData->save();

            $book = Book::findOrFail($book_id);
            $book->status = 'in stock';
            $book->available_copies = $book->available_copies + $copies;
            $book->save();


            if ($comment){

                $commentExists = Comment::where('rent_log_id', $id)->first();

                if ($commentExists){

                    $commentExists->comments = $comment;
                    $commentExists->save();

                } else {

                    Comment::create(
                        [
                            'rent_log_id' => $id,
                            'book_id' => $book_id,
                            'comments' => $comment
                        ]
                    );

                }

            }
            $responseData = [
                'message' => 'Book has been returned',
                'status' => 'success'
            ];

        }else{
            $cmt = Comment::where('rent_log_id',$id)->where('book_id', $book_id)->first();

            if($cmt){

                $cmt->comments = $comment;
                $cmt->save();

                $responseData = [
                    'message' => 'Comment has been updated',
                    'status' => 'success'
                ];

            }else{

                Comment::create(
                    [
                        'rent_log_id' => $id,
                        'book_id' => $book_id,
                        'comments' => $comment
                    ]
                );

                $responseData = [
                    'message' => 'Comment has been added',
                    'status' => 'success'
                ];

            }


        }


        return response()->json($responseData);

    }

    public function returnSecurity(Request $request)
    {
        $id = $request->id;
        $book_id = $request->book_id;
        $security_submitted = $request->security_submitted;
        $security_returned = $request->security_returned;

        $rentData = RentLogs::find($id);
        $rentData->security_returned = $security_returned;
        $rentData->save();

        $book = Book::findOrFail($book_id);
        $book_code = $book->book_code;

        if ($security_returned == 0){

            Session::flash('status', "Security has been set to 0...");

        }else{

            Stripe\Stripe::setApiKey('sk_test_51OuCTPDVHG7hkxkWNKqQstjHSt07DTdHAq87kTdWZEj1OYOdvEr0mlHLfz1o1JyBJQOhwgct5oNr7OUinMBCb7VQ00UEJsmQeV');

            $session = Stripe\Charge::create ([
                "amount" => 100 * $security_returned,
                "currency" => "usd",
                "source" => $request->stripeToken,
                "description" => "Book Code ".$book_code." security has been refunded ".Auth::user()->username
            ]);

            if ($session){
                Session::flash('status', "Security has been refunded...");
            }else{
                Session::flash('status', "Can't refund, something went wrong");
            }

        }



        //return back();

        $responseData = [
            'message' => 'Security has been refunded',
            'status' => 'success'
        ];

        return response()->json($responseData);

    }

    public function updateStatus(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        $isExists = RentLogs::find($id);

        if ($isExists) {
            $isExists->status = $status;
            $isExists->save();
        }

        Session::flash('message', "Status has been updated");
        Session::flash('alert-class', "alert-success");

        $responseData = [
            'message' => 'Status has been updated',
            'status' => 'success'
        ];

        return response()->json($responseData);

    }

}
