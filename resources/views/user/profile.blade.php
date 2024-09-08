@extends('layouts.app')

@section('content')
<style>
    .status-indicator {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 15px;
        height: 15px;
        border-radius: 50%;
        border: 2px solid #fff;
        background-color: #ccc; /* Warna default offline */
    }

    .status-indicator.online {
        background-color: #28a745; /* Warna online */
    }

    .status-indicator.offline {
        background-color: #dc3545; /* Warna offline */
    }
</style>

<div class="container">
    <div class="row mb-4">
        <!-- Foto Profil -->
        <div class="col-md-3 d-flex justify-content-center position-relative">
            @if($user->biodata && $user->biodata->photo)
                <img src="{{ asset('storage/' . $user->biodata->photo) }}" alt="Foto Profil" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
            @else
                <img src="https://via.placeholder.com/150" alt="Foto Default" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
            @endif

            <!-- Indikator Status -->
            <div class="status-indicator {{ $user->is_online ? 'online' : 'offline' }}"></div>
        </div>

        <!-- Informasi Pengguna dan Statistik -->
        <div class="col-md-9 d-flex flex-column justify-content-center">
            <!-- Nama Pengguna dengan Status -->
            <div class="d-flex align-items-center mb-3">
                <h2 class="text-center mb-0">{{ $user->biodata ? $user->biodata->full_name : 'Pengguna' }}</h2>
            </div>

            <!-- Statistik Followers, Following, dan Posts -->
            <div class="row mt-4">
                <div class="col-4 text-center">
                    <a href="{{ route('user.followers', $user->id) }}" class="d-block">
                        <strong style="font-size: 1.25rem;">Pengikut:</strong> {{ $user->followers->count() }}
                    </a>
                </div>
                <div class="col-4 text-center">
                    <a href="{{ route('user.following', $user->id) }}" class="d-block">
                        <strong style="font-size: 1.25rem;">Mengikuti:</strong> {{ $user->following->count() }}
                    </a>
                </div>
                <div class="col-4 text-center">
                    <strong style="font-size: 1.25rem;">Postingan:</strong> {{ $user->posts->count() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Biodata -->
    @if($user->biodata)
        <div class="card mb-3">
            <div class="card-body">
                <h4 class="card-title">{{ $user->biodata->full_name }}</h4>
                <p class="card-text"><strong>Tanggal Lahir:</strong> {{ $user->biodata->birth_date }}</p>
                <p class="card-text"><strong>Tempat Lahir:</strong> {{ $user->biodata->birth_place }}</p>
            </div>
        </div>
    @else
        <p>Tidak ada biodata tersedia.</p>
    @endif

    <!-- Follow/Unfollow dan Tombol Pesan -->
    @auth
        <div class="text-center mb-4">
            <div class="d-flex justify-content-center">
                @if(Auth::user()->isFollowing($user->id))
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

                <!-- Tombol Pesan -->
                @php
                    $chatRoom = \App\Models\ChatRoom::where(function ($query) use ($user) {
                        $query->where('user1_id', Auth::id())
                              ->where('user2_id', $user->id);
                    })->orWhere(function ($query) use ($user) {
                        $query->where('user1_id', $user->id)
                              ->where('user2_id', Auth::id());
                    })->first();
                @endphp

                @if($chatRoom)
                    <a href="{{ route('chat.show', ['id' => $chatRoom->id]) }}" class="btn btn-success btn-sm ml-2"><i class="fas fa-envelope"></i> Pesan</a>
                @else
                    <a href="{{ route('chat.create', ['user_id' => $user->id]) }}" class="btn btn-success btn-sm ml-2"><i class="fas fa-envelope"></i> Pesan</a>
                @endif
            </div>
        </div>
    @endauth

    <!-- Postingan dari Pengguna -->
    <div class="row mt-4">
        @if($user->posts->count())
            @foreach($user->posts as $post)
                <div class="col-md-4 mb-4">
                    <a href="{{ route('posts.show', $post->id) }}" class="d-block">
                        <div class="card" style="cursor: pointer;">
                            @if($post->image)
                                <img src="{{ asset('storage/' . $post->image) }}" class="card-img-top" alt="{{ $post->caption }}">
                            @else
                                <img src="https://via.placeholder.com/500" class="card-img-top" alt="Gambar Postingan">
                            @endif
                            <div class="card-body">
                                <p class="card-text">{{ $post->caption }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        @else
            <p>Belum ada postingan.</p>
        @endif
    </div>
</div>
@endsection
