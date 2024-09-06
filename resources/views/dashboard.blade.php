@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Tampilkan pesan selamat datang -->
    <div class="row align-items-center mb-4">
        <div class="col-md-4 d-flex justify-content-center">
            @if(Auth::user()->biodata && Auth::user()->biodata->photo)
                <img src="{{ asset('storage/' . Auth::user()->biodata->photo) }}" alt="Foto Profil" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
            @else
                <img src="https://via.placeholder.com/150" alt="Foto Default" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
            @endif
        </div>
        <div class="col-md-8">
            <h1>Selamat datang, {{ Auth::user()->name }}!</h1>
            <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
        </div>
    </div>

    @if($posts->count())
        <h2 class="mb-4">Postingan dari Pengguna yang Anda Ikuti</h2>
        <div class="row justify-content-center">
            <div class="col-md-8">
                @foreach($posts as $post)
                    <div class="card mb-4">
                        <div class="card-header d-flex align-items-center">
                            @if($post->user->biodata && $post->user->biodata->photo)
                                <img src="{{ asset('storage/' . $post->user->biodata->photo) }}" alt="Foto Pengguna" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                            @else
                                <img src="https://via.placeholder.com/40" alt="Foto Default" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                            @endif
                            <div class="ml-2">
                                <h6 class="m-0">{{ $post->user->name }}</h6>
                                <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        @if($post->image)
                            <img src="{{ asset('storage/' . $post->image) }}" class="card-img-top" alt="Gambar Postingan">
                        @endif
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <!-- Tombol Like/Unlike dan Komentar -->
                                <div>
                                    <form action="{{ route('post.like', $post->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-link p-0 text-dark">
                                            <i class="fas fa-heart {{ $post->likes->contains(Auth::id()) ? 'text-danger' : 'text-muted' }}"></i>
                                        </button>
                                    </form>
                                    <span>{{ $post->likes->count() }} Suka</span> <!-- Menampilkan jumlah like -->
                                </div>
                                <div>
                                    <span>{{ $post->comments->count() }} Komentar</span> <!-- Menampilkan jumlah komentar -->
                                    <form action="{{ route('post.comment', $post->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-link p-0 text-dark">
                                            <i class="fas fa-comment"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <h5 class="card-title">{{ $post->title }}</h5>
                            <p class="card-text">{{ $post->content }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <form action="{{ route('post.comment', $post->id) }}" method="POST" class="w-100 d-flex">
                                    @csrf
                                    <div class="input-group">
                                        <input type="text" class="form-control comment-input" id="comment" name="comment" placeholder="Tambahkan komentar">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-paper-plane"></i> <!-- Ikon kirim komentar -->
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="mt-3">
                                @foreach($post->comments as $comment)
                                    <div class="media mb-2">
                                        @if($comment->user->biodata && $comment->user->biodata->photo)
                                            <img src="{{ asset('storage/' . $comment->user->biodata->photo) }}" alt="Foto Pengguna" class="mr-3 rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <img src="https://via.placeholder.com/40" alt="Foto Default" class="mr-3 rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                        @endif
                                        <div class="media-body">
                                            <h6 class="mt-0">{{ $comment->user->name }}</h6>
                                            <p>{{ $comment->content }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <p>Anda belum mengikuti pengguna yang memiliki postingan.</p>
    @endif
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

@push('styles')
<style>
    .comment-input {
        width: 100%;
        box-sizing: border-box;
    }

    .bottom-nav {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background-color: #fff;
        border-top: 1px solid #ddd;
        display: flex;
        justify-content: space-around;
        padding: 10px 0;
    }

    .bottom-nav a {
        color: #333;
        text-decoration: none;
        font-size: 24px;
    }

    .bottom-nav a.active {
        color: #007bff;
    }
</style>
@endpush
