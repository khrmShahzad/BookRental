@extends('layouts.mainLayout')

@section('title', 'Profile')

@section('content')

    <div class="row">
        <div class="w-20 col-lg-3 col-md-6">
            <div class="mb-3">
                <label for="" class="form-label">Profile Picture</label>
                <a href="{{ $profile->avatar != null ? asset('users/' . $profile->avatar) : asset('images/images.png') }}" target="_blank">
                    <img class="rounded-circle" src="{{ $profile->avatar != null ? asset('users/' . $profile->avatar) : asset('images/images.png') }}" alt="Profile Picture" style="height: 150px; width: 150px;">
                </a>
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Name</label>
                <input type="text" class="form-control" value="{{ $profile->name }}" readonly>
            </div>

            <div class="mb-3">
                <label for="" class="form-label">Username</label>
                <input type="text" class="form-control" value="{{ $profile->username }}" readonly>
            </div>

            <div class="mb-3">
                <label for="" class="form-label">Phone</label>
                <input type="text" class="form-control" value="{{ $profile->phone }}" readonly>
            </div>

            <div class="mb-3">
                <label for="" class="form-label">Country</label>
                <input type="text" class="form-control" value="{{ $profile->country }}" readonly>
            </div>

            <div class="mb-3">
                <label for="" class="form-label">City</label>
                <input type="text" class="form-control" value="{{ $profile->city }}" readonly>
            </div>

            <div class="mb-3">
                <label for="" class="form-label">Zip Code</label>
                <input type="text" class="form-control" value="{{ $profile->zip_code }}" readonly>
            </div>

            <div class="mb-3">
                <label for="" class="form-label">Address</label>
                <textarea class="form-control" name="" id="" rows="5" readonly style="resize: none">{{ $profile->address }}</textarea>
            </div>
            <div class="mb-3">
                <label for="" class="form-label">Status</label>
                <input type="text" class="form-control" value="{{ $profile->status }}" readonly>
            </div>
        </div>

    </div>

@endsection
