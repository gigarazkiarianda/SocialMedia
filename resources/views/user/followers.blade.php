@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Pengikut {{ $user->name }}</h1>
    <div class="row">
        @forelse($followers as $follower)
            <div class="col-12 col-sm-6 col-md-4 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        @if($follower->biodata && $follower->biodata->photo)
                            <img src="{{ asset('storage/' . $follower->biodata->photo) }}" alt="Foto Pengikut" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                        @else
                            <img src="https://via.placeholder.com/100" alt="Foto Default" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                        @endif
                        <h5 class="card-title mt-2">{{ $follower->name }}</h5>
                        <p class="card-text"><strong>Email:</strong> {{ $follower->email }}</p>

                        @if(Auth::user()->isFollowing($follower->id))
                            <!-- Button for unfollowing the user -->
                            <form action="{{ route('user.unfollow', $follower->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-user-minus"></i> Unfollow</button>
                            </form>
                        @else
                            <!-- Button for following the user -->
                            <form action="{{ route('user.follow', $follower->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-user-plus"></i> Follow</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center">Tidak ada pengikut ditemukan.</p>
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
