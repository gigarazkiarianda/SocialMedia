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
                    <div class="card mb-4 position-relative">
                        <div class="card-header d-flex align-items-center">
                            <div class="d-flex align-items-center flex-grow-1">
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
                            <!-- Tombol tiga titik -->
                            <div class="ml-auto">
                                <button class="btn btn-link text-dark" type="button" data-toggle="modal" data-target="#postActionsModal{{ $post->id }}">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <!-- Modal untuk tindakan postingan -->
                                <div class="modal fade" id="postActionsModal{{ $post->id }}" tabindex="-1" role="dialog" aria-labelledby="postActionsModalLabel{{ $post->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="postActionsModalLabel{{ $post->id }}">Tindakan Postingan</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                @if($post->user_id == Auth::id())
                                                    <a href="{{ route('post.edit', $post->id) }}" class="btn btn-primary btn-block">Edit Post</a>
                                                    <form action="{{ route('post.destroy', $post->id) }}" method="POST" class="d-inline-block w-100">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-block">Hapus Post</button>
                                                    </form>
                                                @else
                                                    <!-- Tombol untuk sembunyikan post -->
                                                    @php
                                                        $isHidden = \App\Models\HiddenPost::where('user_id', Auth::id())->where('post_id', $post->id)->exists();
                                                    @endphp

                                                    @if($isHidden)
                                                        <form action="{{ route('post.unhide', $post->id) }}" method="POST" class="d-inline-block w-100">
                                                            @csrf
                                                            <button type="submit" class="btn btn-secondary btn-block">Tampilkan Post</button>
                                                        </form>
                                                    @else
                                                        <form action="{{ route('post.hide', $post->id) }}" method="POST" class="d-inline-block w-100">
                                                            @csrf
                                                            <button type="submit" class="btn btn-secondary btn-block">Sembunyikan Post</button>
                                                        </form>
                                                    @endif
                                                @endif

                                                <!-- Tombol Share -->
                                                <a href="#" class="btn btn-info btn-block">Bagikan Post</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                                </div>
                            </div>
                            <h5 class="card-title">{{ $post->title }}</h5>
                            <p class="card-text">{{ $post->content }}</p>

                            <!-- Form Input Komentar -->
                            <form action="{{ route('post.comment', $post->id) }}" method="POST" class="w-100 mb-3">
                                @csrf
                                <div class="input-group">
                                    <input type="text" class="form-control" name="comment" placeholder="Tambahkan komentar" required>
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-paper-plane"></i> Kirim
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <!-- Daftar Komentar dan Balasan -->
                            <div class="mt-3">
                                @foreach($post->comments as $comment)
                                    <div class="media mb-3">
                                        @if($comment->user->biodata && $comment->user->biodata->photo)
                                            <img src="{{ asset('storage/' . $comment->user->biodata->photo) }}" alt="Foto Pengguna" class="mr-3 rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <img src="https://via.placeholder.com/40" alt="Foto Default" class="mr-3 rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                        @endif
                                        <div class="media-body">
                                            <h6 class="mt-0">{{ $comment->user->name }}</h6>
                                            <p>{{ $comment->content }}</p>
                                            <!-- Form Input Balasan -->
                                            <form action="{{ route('post.reply', ['post_id' => $post->id, 'comment_id' => $comment->id]) }}" method="POST" class="d-inline-block">
                                                @csrf
                                                <input type="text" name="reply" class="form-control mb-2" placeholder="Balas komentar" required>
                                                <button type="submit" class="btn btn-link p-0">
                                                    <i class="fas fa-reply"></i> Balas
                                                </button>
                                            </form>
                                            <!-- Daftar Balasan Komentar -->
                                            <div class="mt-2">
                                                @foreach($comment->replies as $reply)
                                                    <div class="media mb-2">
                                                        @if($reply->user->biodata && $reply->user->biodata->photo)
                                                            <img src="{{ asset('storage/' . $reply->user->biodata->photo) }}" alt="Foto Pengguna" class="mr-3 rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                                        @else
                                                            <img src="https://via.placeholder.com/40" alt="Foto Default" class="mr-3 rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                                        @endif
                                                        <div class="media-body">
                                                            <h6 class="mt-0">{{ $reply->user->name }}</h6>
                                                            <p>{{ $reply->content }}</p>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
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
        <div class="text-center">
            <p>Tidak ada postingan dari pengguna yang Anda ikuti.</p>
        </div>
    @endif
</div>
@endsection
