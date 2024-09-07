@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <!-- Foto Profil -->
        <div class="col-md-3 d-flex justify-content-center">
            @if($user->biodata && $user->biodata->photo)
                <img src="{{ asset('storage/' . $user->biodata->photo) }}" alt="Foto Profil" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
            @else
                <img src="https://via.placeholder.com/150" alt="Foto Default" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
            @endif
        </div>

        <!-- Informasi Pengguna dan Statistik -->
        <div class="col-md-9 d-flex flex-column justify-content-center">

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

                <!-- Tombol Pesan -->
                @php
                    // Mendapatkan chat room ID antara user yang sedang login dan user yang dilihat
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
                    <div class="card">
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
            <p>Tidak ada postingan tersedia.</p>
        @endif
    </div>
</div>
@endsection
