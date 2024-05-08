@extends('layouts.mainLayout')

@section('title', 'Rented Books')

@section('content')

    <h1>Rented Books</h1>

    @if (session('status'))
        <div class="alert alert-success mt-5">
            {{ session('status') }}
        </div>
    @endif

    <div class="my-5">
        <x-rent-log-table1 :rentlog='$rent_logs' />
    </div>

    <div class="my-5">
        {{ $rent_logs->withQueryString()->links() }}
    </div>
@endsection
