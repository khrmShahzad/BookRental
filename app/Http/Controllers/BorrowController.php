<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Stripe\Stripe;

class BorrowController extends Controller
{
    public function index(Request $request, $id)
    {
        $book = Book::find($id);
        return view('borrow', ['book' => $book]);
    }

    public function checkout(Request $request)
    {

        \Stripe\Stripe::setApiKey(config('stripe.sk'));

        $productName = 'test';
        $total = 100;
        $id = 1;
        /*$session = \Stripe\Checkout\Session::create([
           'line_items' => [
               'price_data' => [
                   'currency' => 'USD',
                   'product_data' => [
                       'name' => $productName
                   ],
                   'unit_amount' => $total,
                   'quantity' => 1
               ],
           ],
            'mode' => 'payment',
            /*'success_url' => action([BorrowController::class, 'success']),
            'cancel_url' =>  action([PublicController::class, 'index']),* /

            'success_url' => route('success'),
            'cancel_url'  => route('checkout'),
        ]);*/


        $session = \Stripe\Checkout\Session::create([
            'line_items'  => [
                [
                    'price_data' => [
                        'currency'     => 'USD',
                        'product_data' => [
                            "name" => $productName,
                        ],
                        'unit_amount'  => $total,
                    ],
                    'quantity'   => 1,
                ],

            ],
            'mode'        => 'payment',
            'success_url' => route('success'),
            'cancel_url'  => route('checkout'),
        ]);

        $url = route('borrow.request', ['id' => $id]);

        // You can use the generated URL as needed, for example, for redirection
        return redirect($url);

//        return redirect()->route('borrow-req', ['id' => $id]);


//        $book = Book::find($id);
//        return view('borrow', ['book' => $book]);
    }

    public function checkout2()
    {
        dd('checkout2');
    }

    public function success(Request $request)
    {
        dd('success');
        Session::flash('message', "Rent Book Success");
        Session::flash('alert-class', "alert-success");
        return view('book-list');
    }

}
