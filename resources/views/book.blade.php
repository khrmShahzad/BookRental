@extends('layouts.mainLayout')

@section('title', 'Book')

@section('content')

    <h1>Book List</h1>

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

    <div class="mt-5 row d-flex justify-content-between">
        <div class="col-12 col-sm-5 mb-3">
            <form action="" method="get" class="">
                <div class="input-group">
                    <input type="text" class="form-control" id="floatingInputGroup1" name="keyword"
                        placeholder="Search Keyword">
                    <button class="input-group-text btn btn-primary">Search</button>
                </div>
            </form>
        </div>

        <div class="col-12 col-md-auto">
            <a href="book-add" class="btn btn-primary me-4">Add Data</a>
            <a href="book-deleted" class="btn btn-info">Show Deleted Data</a>
        </div>
    </div>

    <div class="my-5">
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th class="col-sm-1">No.</th>
                    <th class="col-sm-1">Cover</th>
                    <th class="col-sm-1">Code</th>
                    <th class="col-sm-2">Title</th>
                    <th class="col-sm-2">Category</th>
                    <th class="col-sm-2">Status</th>
                    <th class="col-sm-1">Price</th>
                    <th class="col-sm-1">Total Copies</th>
                    <th class="col-sm-1">Available Copies</th>
                    <th class="col-sm-2">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($books as $item)
                    <tr>
                        <td>{{ $loop->iteration + $books->firstItem() - 1 }}</td>
                        <td><img src="{{ $item->cover != null ? asset('cover/' . $item->cover) : asset('cover/cover-default.png') }}"
                                 class="card-img-top img-fluid" alt="Book Cover" draggable="false" height="250px"></td>
                        <td>{{ $item->book_code }}</td>
                        <td>{{ $item->title }}</td>
                        <td>
                            @foreach ($item->categories as $category)
                                | {{ $category->name }} |
                            @endforeach
                        </td>
                        <td>{{ $item->status }}</td>
                        <td>{{ $item->charges }}</td>
                        <td>{{ $item->total_copies }}</td>
                        <td>{{ $item->available_copies }}</td>
                        <td>
                            <a href="book-edit/{{ $item->slug }}" class="btn btn-warning me-2">Edit</a>
                            <a href="book-delete/{{ $item->slug }}" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="my-5">
        {{ $books->withQueryString()->links() }}
    </div>
@endsection
