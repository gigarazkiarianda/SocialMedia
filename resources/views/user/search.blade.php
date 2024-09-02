@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Search Results for "{{ $query }}"</h1>

    @if($users->isEmpty())
        <p>No users found.</p>
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
@endsection
