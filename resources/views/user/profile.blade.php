@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex align-items-center mb-4">
        @if($user->biodata && $user->biodata->photo)
            <img src="{{ asset('storage/' . $user->biodata->photo) }}" alt="Profile Photo" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
        @else
            <img src="https://via.placeholder.com/150" alt="Default Photo" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
        @endif

        <div class="ml-4">
            <h1>{{ $user->name }}</h1>
            <p><strong>Email:</strong> {{ $user->email }}</p>
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
    @endauth
</div>
@endsection
