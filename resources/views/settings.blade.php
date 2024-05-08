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
                    <a href="{{ $profile->avatar != null ? asset('users/' . $profile->avatar) : asset('images/images.png') }}" target="_blank"><img class="rounded-circle" src="{{ $profile->avatar != null ? asset('users/' . $profile->avatar) : asset('images/images.png') }}" alt="Profile Picture" style="height: 150px; width: 150px;"></a>
                    <input type="file" class="form-control" name="image" id="image">
                </div>
                <div class="mb-3">
                    <label for="" class="form-label">Name</label>
                    <input type="text" class="form-control" name="name" value="{{ $profile->name }}">
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
                    <label for="" class="form-label">Country</label>
                    <input type="text" class="form-control" name="country" value="{{ $profile->country }}">
                </div>

                <div class="mb-3">
                    <label for="" class="form-label">City</label>
                    <input type="text" class="form-control" name="city" value="{{ $profile->city }}">
                </div>

                <div class="mb-3">
                    <label for="" class="form-label">Zip Code</label>
                    <input type="text" class="form-control" name="zip_code" value="{{ $profile->zip_code }}">
                </div>


                <div class="mb-3">
                    <label for="" class="form-label">Address</label>
                    <textarea class="form-control" id="" rows="5" name="address" style="resize: none">{{ $profile->address }}</textarea>
                </div>
                <div class="text-center"><button type="submit">Update</button></div>
            </form>
        </div>
    </div>

@endsection
