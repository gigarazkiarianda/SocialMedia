@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card mb-3" style="max-width: 100%; overflow-y: auto;">
        @if($post->image)
            <img src="{{ asset('storage/' . $post->image) }}" class="card-img-top" alt="Gambar Post" style="object-fit: contain;">
        @endif
        <div class="card-body">
            <h5 class="card-title">{{ $post->title }}</h5>
            <p class="card-text">{{ $post->content }}</p>

            <!-- Tampilkan komentar -->
            <h6 class="mt-3">Komentar:</h6>
            @foreach($post->comments as $comment)
                <div class="media mb-2">
                    <img src="{{ $comment->user->biodata && $comment->user->biodata->photo ? asset('storage/' . $comment->user->biodata->photo) : 'https://via.placeholder.com/30' }}" class="mr-3 rounded-circle" style="width: 30px; height: 30px; object-fit: cover;" alt="Foto Pengguna">
                    <div class="media-body">
                        <h6 class="mt-0">{{ $comment->user->name }}</h6>
                        <p>{{ $comment->content }}</p>
                    </div>
                </div>
            @endforeach

            <!-- Formulir komentar -->
            <form action="{{ route('post.addComment', $post->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="comment">Tambahkan Komentar:</label>
                    <textarea class="form-control" id="comment" name="comment" rows="2" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Kirim Komentar</button>
            </form>
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
    .card-body {
        max-height: 300px; /* Tinggi kartu disesuaikan lebih kecil */
        overflow-y: auto; /* Aktifkan scroll vertikal */
    }
    .card-img-top {
        max-width: 100%; /* Menjaga lebar gambar tetap dalam batas kartu */
        max-height: 150px; /* Sesuaikan tinggi gambar lebih kecil */
        object-fit: contain; /* Menjaga rasio aspek gambar */
    }
    .media-body {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .card-title {
        font-size: 1rem; /* Ukuran teks judul lebih kecil */
    }
    .card-text {
        font-size: 0.85rem; /* Ukuran teks konten lebih kecil */
    }
    .form-control {
        font-size: 0.8rem; /* Ukuran teks area komentar lebih kecil */
    }
    button.btn {
        font-size: 0.8rem; /* Ukuran teks tombol lebih kecil */
    }
</style>
@endpush
