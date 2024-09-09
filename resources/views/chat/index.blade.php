@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Chat Rooms</h1>

    <!-- Add Chat Room Form -->
    <form action="{{ route('chat.store') }}" method="POST" class="mb-4">
        @csrf
        <div class="form-group">
            <label for="user">Select User to Chat With</label>
            <select id="user" name="user_id" class="form-control" required>
                <option value="" disabled selected>Select a user</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Start Chat</button>
    </form>

    <!-- Chat Rooms List -->
    <ul class="list-group">
        @foreach($chatRooms as $chatRoom)
            <li class="list-group-item d-flex align-items-center">
                @php
                    $otherUser = $chatRoom->user1->id === Auth::id() ? $chatRoom->user2 : $chatRoom->user1;
                @endphp

                <!-- User Name and Recent Message -->
                <div class="d-flex align-items-center mr-3">
                    @if($otherUser->biodata && $otherUser->biodata->photo)
                        <img src="{{ asset('storage/' . $otherUser->biodata->photo) }}" alt="Profile Photo" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                    @else
                        <img src="https://via.placeholder.com/100" alt="Default Photo" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                    @endif

                    <div class="ml-3">
                        <strong>{{ $otherUser->name }}</strong><br>
                        @php
                            $latestMessage = $chatRoom->messages->last();
                        @endphp
                        @if($latestMessage)
                            <span>{{ $latestMessage->message }}</span>
                            <span class="message-status">
                                @if($latestMessage->seen === 0)
                                    <i class="fas fa-check-circle text-muted"></i> <!-- Pending -->
                                @elseif($latestMessage->seen === 1)
                                    <i class="fas fa-check-circle text-primary"></i> <!-- Sent -->
                                @elseif($latestMessage->seen === 2)
                                    <i class="fas fa-check-double text-success"></i> <!-- Read -->
                                @endif
                            </span>
                        @else
                            <span>No messages yet.</span>
                        @endif
                    </div>
                </div>

                <!-- View Chat Button -->
                <a href="{{ route('chat.show', $chatRoom->id) }}" class="btn btn-info btn-sm ml-auto">View Chat</a>
            </li>
        @endforeach
    </ul>
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

@section('styles')
<style>
    .message-status {
        display: inline-block;
        margin-left: 10px;
    }
    .message-status .fa-check-circle {
        font-size: 1.2em;
    }
    .message-status .fa-check-double {
        font-size: 1.2em;
    }
</style>
@endsection
@endsection
