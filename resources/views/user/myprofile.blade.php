@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Bagian Profil -->
    <div class="row align-items-center mb-4">
        <div class="col-12 col-md-4 d-flex justify-content-center mb-3 mb-md-0">
            @if(Auth::user()->biodata && Auth::user()->biodata->photo)
                <img src="{{ asset('storage/' . Auth::user()->biodata->photo) }}" alt="Foto Profil" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
            @else
                <img src="https://via.placeholder.com/150" alt="Foto Default" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
            @endif
        </div>

        <div class="col-12 col-md-8">
            <h1>{{ Auth::user()->name }}</h1>
        </div>
    </div>

     <!-- Pengikut, Mengikuti, dan Postingan -->
     <div class="row text-center mb-4">
        <div class="col-4">
            <h4 class="text-dark">Postingan</h4>
            <p class="text-dark">{{ $posts->count() }}</p>
        </div>
        <div class="col-4">
            <h4><a href="{{ route('user.followers', Auth::user()->id) }}" class="text-dark">Pengikut</a></h4>
            <p class="text-dark">{{ Auth::user()->followers->count() }}</p>
        </div>
        <div class="col-4">
            <h4><a href="{{ route('user.following', Auth::user()->id) }}" class="text-dark">Mengikuti</a></h4>
            <p class="text-dark">{{ Auth::user()->following->count() }}</p>
        </div>
    </div>

    <!-- Bagian Biodata -->
    @if($biodata)
        <div class="card mb-3">
            <div class="card-body">
                <h4 class="card-title">{{ $biodata->full_name }}</h4>
                <p class="card-text"><strong>Tanggal Lahir:</strong> {{ $biodata->birth_date }}</p>
                <p class="card-text"><strong>Tempat Lahir:</strong> {{ $biodata->birth_place }}</p>
                <a href="{{ route('biodata.edit', $biodata->id) }}" class="btn btn-primary">Edit Biodata</a>
            </div>
        </div>
    @else
        <p>Tidak ada biodata. <a href="{{ route('biodata.create') }}">Buat sekarang</a>.</p>
    @endif

    <!-- Bagian Grid Postingan -->
    <h2 class="mt-4 mb-3">Postingan Anda</h2>
    <div class="row">
        @forelse($posts as $post)
            <div class="col-12 col-sm-6 col-md-4 mb-4">
                <a href="{{ route('posts.show', $post->id) }}" class="card-link">
                    <div class="card">
                        <img src="{{ asset('storage/' . $post->image) }}" class="card-img-top" alt="Gambar Postingan">
                    </div>
                </a>
            </div>
        @empty
            <p class="text-center">Anda belum memiliki postingan.</p>
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

@push('styles')
<style>
    .card-img-top {
        width: 100%;
        height: auto;
        object-fit: cover;
        aspect-ratio: 1/1;
    }
    .bottom-nav {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: #fff;
        border-top: 1px solid #ddd;
        display: flex;
        justify-content: space-around;
        padding: 10px 0;
    }
    .bottom-nav a {
        color: #333;
        font-size: 24px;
    }
    .bottom-nav a.active {
        color: #007bff;
    }
    .card-link {
        text-decoration: none;
        color: inherit;
    }
    .card-link:hover .card {
        border-color: #007bff;
    }
</style>
@endpush
