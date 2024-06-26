@extends('layouts.mainLayout')

@section('title', 'Add Book')

@section('content')

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <h1>Add New Book</h1>

    <div>

        <div class="mt-5 w-50">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="book-add" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="code" class="form-label fw-bold">Code</label>
                    <input type="text" class="form-control" name="book_code" id="code" placeholder="Book Code- Auto Generate"
                        value="{{ old('book_code') }}" readonly>
                </div>
                <div class="mb-3">
                    <label for="title" class="form-label fw-bold">Title</label>
                    <input type="text" class="form-control" name="title" id="title" placeholder="Book Title"
                        value="{{ old('title') }}">
                </div>
                <div class="mb-3">
                    <label for="title" class="form-label fw-bold">Author Name</label>
                    <input type="text" class="form-control" name="author" id="author" placeholder="Author Name"
                           value="{{ old('author') }}">
                </div>
                <div class="mb-3">
                    <label for="title" class="form-label fw-bold">Description</label>
                    <input type="text" class="form-control" name="description" id="description" placeholder="Book Description"
                           value="{{ old('description') }}">
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label fw-bold">Cover Image</label>
                    <input type="file" class="form-control" name="image" id="image">
                </div>

                <div class="mb-3">
                    <label for="charges" class="form-label fw-bold">Price</label>
                    <input type="number" class="form-control" name="charges" id="charges" placeholder="Book Charges"
                           value="{{ old('charges') }}">
                </div>

                <div class="mb-3">
                    <label for="charges" class="form-label fw-bold">Security</label>
                    <input type="number" class="form-control" name="security" id="security" placeholder="Book Security"
                           value="{{ old('security') }}">
                </div>

                <div class="mb-3">
                    <label for="total_copies" class="form-label fw-bold">Total Copies</label>
                    <input type="number" class="form-control" name="total_copies" id="total_copies" placeholder="Total Copies"
                           value="{{ old('total_copies') }}">
                </div>

                <div class="mb-3">
                    <label for="available_copies" class="form-label fw-bold">Available Copies</label>
                    <input type="number" class="form-control" name="available_copies" id="available_copies" placeholder="Available Copies"
                           value="{{ old('available_copies') }}">
                </div>

                <div class="mb-3">
                    <label for="category" class="form-label fw-bold">Category</label>
                    <select name="categories[]" id="category" class="form-control select-multiple" multiple="multiple">
                        @foreach ($categories as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
        </div>
        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        // In your Javascript (external .js resource or <script> tag)
        $(document).ready(function() {
            $('.select-multiple').select2();
        });
    </script>
@endsection
