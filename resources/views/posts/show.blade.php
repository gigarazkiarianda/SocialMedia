@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card mb-3">
        @if($post->image)
            <img src="{{ asset('storage/' . $post->image) }}" class="card-img-top" alt="Gambar Post">
        @endif
        <div class="card-body">
            <h5 class="card-title">{{ $post->title }}</h5>
            <p class="card-text">{{ $post->content }}</p>

            <!-- Tampilkan komentar -->
            <h6 class="mt-3">Komentar:</h6>
            @foreach($post->comments as $comment)
                <div class="comment mb-3">
                    <div class="d-flex align-items-start">
                        <img src="{{ $comment->user->biodata && $comment->user->biodata->photo ? asset('storage/' . $comment->user->biodata->photo) : 'https://via.placeholder.com/50' }}" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;" alt="Foto Pengguna">
                        <div class="ml-3">
                            <h6 class="font-weight-bold">{{ $comment->user->name }}</h6>
                            <p>{{ $comment->content }}</p>

                            <!-- Tampilkan balasan komentar -->
                            @foreach($comment->replies as $reply)
                                <div class="reply ml-4">
                                    <strong>{{ $reply->user->name }}</strong>
                                    <p>{{ $reply->content }}</p>
                                </div>
                            @endforeach

                            <!-- Formulir balasan komentar -->
                            <form action="{{ route('post.reply', ['post_id' => $post->id, 'comment_id' => $comment->id]) }}" method="POST">
                                @csrf
                                <div class="input-group mt-2">
                                    <input type="text" name="reply_text" placeholder="Balas komentar ini..." class="form-control" required>
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary">Balas</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
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

@push('styles')
<style>
    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .card-img-top {
        max-width: 100%;
        height: auto;
        object-fit: cover;
    }
    .card-body {
        padding: 16px;
    }
    .comment {
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
    }
    .reply {
        border-bottom: 1px solid #f1f1f1;
        padding-bottom: 5px;
    }
    .input-group {
        margin-top: 10px;
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
        font-size: 20px;
    }
    .bottom-nav a.active {
        color: #007bff;
    }
</style>
@endpush
