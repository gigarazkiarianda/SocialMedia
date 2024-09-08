@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Hasil Pencarian untuk "{{ $query }}"</h1>

    @if($users->isEmpty())
        <p>Tidak ada pengguna ditemukan.</p>
    @else
        <ul class="list-group">
            @foreach($users as $user)
                <li class="list-group-item">
                    <a href="{{ route('user.profile', $user->id) }}">{{ $user->name }}</a> ({{ $user->email }})
                </li>
            @endforeach
        </ul>
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
