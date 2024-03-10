@extends('layouts.mainLayout')

@section('title', 'Book')

@section('content')

    <h1>Borrow A Book</h1>

    @if (session('status'))
        <div class="alert alert-success mt-5">
            {{ session('status') }}
        </div>
        @if (Session::get('message'))
            <div class="alert alert-warning" role="alert">
                {{ Session::get('message') }}
            </div>
        @endif
    @endif


    <div class="my-5">

{{--        <tr>--}}
{{--            <td><img src="{{ $books->cover != null ? asset('cover/' . $books->cover) : asset('images/cover-default.png') }}"--}}
{{--                     class="card-img-top img-fluid" alt="Book Cover" draggable="false" height="250px"></td>--}}
{{--            <td>{{ $books->book_code }}</td>--}}
{{--            <td>{{ $books->title }}</td>--}}
{{--            <td>--}}
{{--                @foreach ($books->categories as $category)--}}
{{--                    | {{ $category->name }} |--}}
{{--                @endforeach--}}
{{--            </td>--}}
{{--            <td>{{ $books->status }}</td>--}}
{{--            <td>{{ $books->charges }}</td>--}}
{{--            <td>--}}
{{--                <a href="book-edit/{{ $books->slug }}" class="btn btn-warning me-2">Edit</a>--}}
{{--                <a href="book-delete/{{ $books->slug }}" class="btn btn-danger">Delete</a>--}}
{{--            </td>--}}
{{--        </tr>--}}


        <div class="row">
{{--            @dd($books)--}}
            <div class="my-5 w-20 col-lg-3 col-md-6">
                <form action="/checkout" method="POST" role="form" class="php-email-form" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="" class="form-label">Book Cover</label>
                        <img src="{{ $book->cover != null ? asset('cover/' . $book->cover) : asset('images/cover-default.png') }}"
                             alt="Book Cover" height="150px">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Book Code</label>
                        <input type="text" class="form-control" name="book_code" readonly value="{{ $book->book_code }}">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Status</label>
                        <input type="text" class="form-control" name="status" value="{{ $book->status }}">
                    </div>
                    <div class="mb-3">
                        <label for="" class="form-label">Charges</label>
                        <input type="text" class="form-control" name="charges" value="{{ $book->charges }}">
                    </div>
                    <div class="text-center"><button type="submit">Checkout</button></div>
                </form>
            </div>
        </div>
    </div>

@endsection
