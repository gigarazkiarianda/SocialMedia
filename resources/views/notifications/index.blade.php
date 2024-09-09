@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h1>Notifikasi</h1>

        @if($notifications->isEmpty())
            <div class="alert alert-info">
                Tidak ada notifikasi baru.
            </div>
        @else
            <div class="list-group">
                @foreach ($notifications as $notification)
                    @php
                        // Memastikan bahwa data adalah string sebelum decode
                        $data = is_string($notification->data) ? json_decode($notification->data, true) : $notification->data;
                    @endphp

                    <div class="list-group-item d-flex justify-content-between align-items-center {{ $notification->read ? 'list-group-item-light' : 'list-group-item-primary' }}">
                        <div>
                            @switch($notification->type)
                                @case('follow')
                                    <strong>{{ $data['user_name'] ?? 'User' }}</strong> mulai mengikuti Anda.
                                    @break
                                @case('like')
                                    <strong>{{ $data['user_name'] ?? 'User' }}</strong> menyukai foto Anda.
                                    @break
                                @case('comment')
                                    <strong>{{ $data['user_name'] ?? 'User' }}</strong> mengomentari foto Anda: "{{ $data['comment_content'] ?? 'Komentar' }}"
                                    @break
                                @default
                                    Notifikasi baru
                            @endswitch
                        </div>

                        <small class="text-muted">
                            {{ $notification->created_at->diffForHumans() }}
                        </small>

                        <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm {{ $notification->read ? 'disabled' : '' }}">
                                Tandai sebagai dibaca
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
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
