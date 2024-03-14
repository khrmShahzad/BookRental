<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\RentLogController;
use App\Http\Controllers\BookRentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BorrowController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [PublicController::class, 'index']);
Route::get('book-detail/{id}', [BookController::class, 'details']);

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('login', [AuthController::class, 'authenticating']);
    Route::get('register', [AuthController::class, 'register']);
    Route::post('register', [AuthController::class, 'registerProcess']);
});

Route::middleware('auth')->group(function () {
    Route::get('logout', [AuthController::class, 'logout']);

    Route::get('profile', [UserController::class, 'profile']);//->middleware('only_client');

    Route::get('settings', [UserController::class, 'settings']);
    Route::post('settings', [UserController::class, 'update']);

    Route::get('book-rent', [BookRentController::class, 'index']);
    Route::post('book-rent', [BookRentController::class, 'store']);
    Route::get('rent-logs', [RentLogController::class, 'index']);
    Route::get('book-return', [BookRentController::class, 'returnBook']);
    Route::get('get-user-books/{user_id}', [BookRentController::class, 'getUserBooks']);
    Route::post('book-return', [BookRentController::class, 'saveReturnBook']);
    Route::post('rate-book', [BookRentController::class, 'rateBook']);
    Route::post('return-book', [BookRentController::class, 'returnBookNew']);

    Route::middleware('only_client')->group(function () {
//        Route::get('borrow-req/{id}', [BorrowController::class, 'index']);
        Route::get('borrow-req/{id}', [BorrowController::class, 'index'])->name('borrow.request');

        Route::post('checkout', [BorrowController::class, 'checkout']);


//        Route::get('/checkout', 'App\Http\Controllers\BorrowController@checkout2')->name('checkout');
        Route::get('/success', 'App\Http\Controllers\BorrowController@success')->name('success');
    });

    Route::middleware('only_admin')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index']);

        Route::get('books', [BookController::class, 'index']);
        Route::get('book-add', [BookController::class, 'add']);
        Route::post('book-add', [BookController::class, 'store']);
        Route::get('book-edit/{slug}', [BookController::class, 'edit']);
        Route::put('book-edit/{slug}', [BookController::class, 'update']);
        Route::get('book-delete/{slug}', [BookController::class, 'delete']);
        Route::delete('book-delete/{slug}', [BookController::class, 'destroy']);
        Route::get('book-deleted', [BookController::class, 'deletedBook']);
        Route::get('book-restore/{slug}', [BookController::class, 'restore']);
        Route::delete('book-permanent-delete/{slug}', [BookController::class, 'permanentDelete']);

        Route::get('categories', [CategoryController::class, 'index']);
        Route::get('category-add', [CategoryController::class, 'add']);
        Route::post('category-add', [CategoryController::class, 'store']);
        Route::get('category-edit/{slug}', [CategoryController::class, 'edit']);
        Route::put('category-edit/{slug}', [CategoryController::class, 'update']);
        Route::get('category-delete/{slug}', [CategoryController::class, 'delete']);
        Route::delete('category-delete/{slug}', [CategoryController::class, 'destroy']);
        Route::get('category-deleted', [CategoryController::class, 'deletedCategory']);
        Route::get('category-restore/{slug}', [CategoryController::class, 'restore']);
        Route::delete('category-permanent-delete/{slug}', [CategoryController::class, 'permanentDelete']);

        Route::get('users', [UserController::class, 'index']);
        Route::get('usersss', [UserController::class, 'usersss']);
        Route::get('registered-users', [UserController::class, 'registeredUsers']);
        Route::get('user-detail/{slug}', [UserController::class, 'show']);
        Route::get('user-approve/{slug}', [UserController::class, 'approve']);
        Route::get('user-ban/{slug}', [UserController::class, 'delete']);
        Route::delete('user-ban/{slug}', [UserController::class, 'destroy']);
        Route::get('user-deleted', [UserController::class, 'bannedUsers']);
        Route::get('user-restore/{slug}', [UserController::class, 'restore']);
        Route::delete('user-permanent-ban/{slug}', [UserController::class, 'permanentDelete']);


    });
});
