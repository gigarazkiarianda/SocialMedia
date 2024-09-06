@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Following {{ $user->name }}</h1>
    <div class="row">
        @forelse($following as $followingUser)
            <div class="col-12 col-sm-6 col-md-4 mb-4">
                <div class="card">
                    <a href="{{ route('user.profile', $followingUser->id) }}" class="text-decoration-none">
                        <div class="card-body text-center">
                            @if($followingUser->biodata && $followingUser->biodata->photo)
                                <img src="{{ asset('storage/' . $followingUser->biodata->photo) }}" alt="Following User Photo" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                            @else
                                <img src="https://via.placeholder.com/100" alt="Default Photo" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                            @endif
                            <h5 class="card-title mt-2">{{ $followingUser->name }}</h5>
                            <p class="card-text"><strong>Email:</strong> {{ $followingUser->email }}</p>
                        </div>
                    </a>
                    <div class="card-footer text-center">
                        @if(Auth::user()->isFollowing($followingUser->id))
                            <form action="{{ route('user.unfollow', $followingUser->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-user-minus"></i> Unfollow</button>
                            </form>
                        @else
                            <form action="{{ route('user.follow', $followingUser->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-user-plus"></i> Follow</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center">You are not following anyone yet.</p>
        @endforelse
    </div>
</div>

 <!-- Bottom Navigation Bar -->
 <div class="bottom-nav">
    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="fas fa-home"></i>
    </a>
    <a href="{{ route('chat.index') }}" class="{{ request()->routeIs('chat.index') ? 'active' : '' }}">
        <i class="fas fa-comments"></i>
    </a>
    <a href="{{ route('post.create') }}" class="{{ request()->routeIs('post.create') ? 'active' : '' }}">
        <i class="fas fa-plus"></i>
    </a>
    <a href="{{ route('user.myprofile') }}" class="{{ request()->routeIs('user.myprofile') ? 'active' : '' }}">
        <i class="fas fa-user"></i>
    </a>
</div>
@endsection
