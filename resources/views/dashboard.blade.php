@extends('layouts.app')

@section('content')
<div class="container">

    <!-- Display welcome message -->
    <div class="row align-items-center mb-4">
        <div class="col-md-4 d-flex justify-content-center">
            @if(Auth::user()->biodata && Auth::user()->biodata->photo)
                <img src="{{ asset('storage/' . Auth::user()->biodata->photo) }}" alt="Profile Photo" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
            @else
                <img src="https://via.placeholder.com/150" alt="Default Photo" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
            @endif
        </div>
        <div class="col-md-8">
            <h1>Welcome, {{ Auth::user()->name }}!</h1>
            <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
        </div>
    </div>

    <!-- Display users that the logged-in user is following -->
    @if($followingUsers->count())
        <h2 class="mb-4">You Are Following</h2>
        <div class="row">
            @foreach($followingUsers as $followingUser)
                <div class="col-sm-6 col-md-4 mb-4">
                    <a href="{{ route('user.profile', $followingUser->id) }}" class="text-decoration-none">
                        <div class="card">
                            <div class="card-body text-center">
                                @if($followingUser->biodata && $followingUser->biodata->photo)
                                    <img src="{{ asset('storage/' . $followingUser->biodata->photo) }}" alt="User Photo" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                                @else
                                    <img src="https://via.placeholder.com/100" alt="Default Photo" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                                @endif
                                <h5 class="card-title mt-2">{{ $followingUser->name }}</h5>
                                <p class="card-text"><strong>Email:</strong> {{ $followingUser->email }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @else
        <p>You are not following anyone yet.</p>
    @endif

</div>
@endsection
