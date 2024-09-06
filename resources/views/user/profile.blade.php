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

    <!-- Biodata -->
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
                    <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-user-minus"></i> Unfollow</button>
                </form>
            @else
                <form action="{{ route('user.follow', $user->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-user-plus"></i> Follow</button>
                </form>
            @endif
        </div>
    @endauth

    <!-- Postingan dari Pengguna -->
    <div class="row mt-4">
        @if($user->posts->count())
            @foreach($user->posts as $post)
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header d-flex align-items-center">
                            @if($post->user->biodata && $post->user->biodata->photo)
                                <img src="{{ asset('storage/' . $post->user->biodata->photo) }}" alt="User Photo" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                            @else
                                <img src="https://via.placeholder.com/40" alt="Default Photo" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                            @endif
                            <div class="ml-2">
                                <h6 class="m-0">{{ $post->user->name }}</h6>
                                <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        @if($post->image)
                            <img src="{{ asset('storage/' . $post->image) }}" class="card-img-top" alt="Post Image">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $post->title }}</h5>
                            <p class="card-text">{{ $post->content }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    @if($post->likes->contains(Auth::id()))
                                        <form action="{{ route('post.like', $post->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-heart"></i> Unlike</button>
                                        </form>
                                    @else
                                        <form action="{{ route('post.like', $post->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-primary btn-sm"><i class="fas fa-heart"></i> Like</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <p>{{ $user->name }} belum memiliki postingan.</p>
        @endif
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
