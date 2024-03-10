@extends('layouts.mainLayout')

@section('title', 'Profile')

@section('content')

    @include('notifications')

    <div class="row">
        <div class="my-5 w-20 col-lg-3 col-md-6">
            <form action="/settings" method="POST" role="form" class="php-email-form" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="" class="form-label">Profile Picture</label>
                    <input type="file" class="form-control" name="image" id="image">
                    <img src="{{ $profile->avatar != null ? asset('users/' . $profile->avatar) : asset('images/images.png') }}"
                         alt="Profile Picture" height="150px">
                </div>
                <div class="mb-3">
                    <label for="" class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" readonly value="{{ $profile->username }}">
                </div>
                <div class="mb-3">
                    <label for="" class="form-label">Phone</label>
                    <input type="text" class="form-control" name="phone" value="{{ $profile->phone }}">
                </div>
                <div class="mb-3">
                    <label for="" class="form-label">Address</label>
                    <textarea class="form-control" name="" id="" rows="5" name="address" style="resize: none">{{ $profile->address }}</textarea>
                </div>
                {{--<div class="mb-3">
                    <label for="" class="form-label">Status</label>
                    <input type="text" class="form-control" value="{{ $profile->status }}">
                </div>--}}
                <div class="text-center"><button type="submit">Update</button></div>
            </form>
        </div>
    </div>

    {{--<div class="my-5">
        <h2 class="mb-3">Your Rent Logs</h2>
        <x-rent-log-table :rentlog='$rent_logs' />
    </div>--}}
@endsection
