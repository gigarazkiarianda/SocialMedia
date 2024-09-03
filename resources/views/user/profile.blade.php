@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-4 d-flex justify-content-center">
            @if($user->biodata && $user->biodata->photo)
                <img src="{{ asset('storage/' . $user->biodata->photo) }}" alt="Profile Photo" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
            @else
                <img src="https://via.placeholder.com/150" alt="Default Photo" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
            @endif
        </div>
        <div class="col-md-8 d-flex flex-column justify-content-center">
            <h1>{{ $user->name }}</h1>
            <p><strong>Email:</strong> {{ $user->email }}</p>
        </div>
    </div>

     <!-- Followers and Following Counts -->
     <div class="mb-4">
        <div class="row">
            <div class="col-12 col-md-6 text-center text-md-left mb-2 mb-md-0">
                <a href="{{ route('user.followers', $user->id) }}" class="d-block">
                    <strong>Followers:</strong> {{ $user->followers->count() }}
                </a>
            </div>
            <div class="col-12 col-md-6 text-center text-md-right">
                <a href="{{ route('user.following', $user->id) }}">
                    <strong>Following:</strong> {{ $user->following->count() }}
                </a>
            </div>
        </div>
    </div>


    @if($biodata)
        <div class="card mb-3">
            <div class="card-body">
                <h4 class="card-title">{{ $biodata->full_name }}</h4>
                <p class="card-text"><strong>Birth Date:</strong> {{ $biodata->birth_date }}</p>
                <p class="card-text"><strong>Birth Place:</strong> {{ $biodata->birth_place }}</p>
            </div>
        </div>
    @else
        <p>No biodata available.</p>
    @endif


    <!-- Follow/Unfollow Button -->
    @auth
        <div class="text-center">
            @if(Auth::user()->following->contains($user))
                <form action="{{ route('user.unfollow', $user->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button class="btn btn-danger" type="submit">Unfollow</button>
                </form>
            @else
                <form action="{{ route('user.follow', $user->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button class="btn btn-primary" type="submit">Follow</button>
                </form>
            @endif
        </div>
    @endauth
</div>
@endsection
