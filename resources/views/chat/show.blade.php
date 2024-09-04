@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Chat with {{ $chatRoom->user1->id === Auth::id() ? $chatRoom->user2->name : $chatRoom->user1->name }}</h1>

    <div class="d-flex align-items-center mb-4">
        <!-- Profile Picture -->
        @php
            $otherUser = $chatRoom->user1->id === Auth::id() ? $chatRoom->user2 : $chatRoom->user1;
        @endphp
        @if($otherUser->biodata && $otherUser->biodata->photo)
            <img src="{{ asset('storage/' . $otherUser->biodata->photo) }}" alt="Profile Photo" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
        @else
            <img src="https://via.placeholder.com/150" alt="Default Photo" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
        @endif

        <!-- User Name -->
        <div class="ml-3">
            <h3>{{ $otherUser->name }}</h3>
        </div>
    </div>

    <!-- Chat Messages -->
    <div class="chat-messages mb-4" style="height: 400px; overflow-y: scroll; border: 1px solid #ddd; padding: 10px;">
        @foreach($chatRoom->messages as $message)
            <div class="mb-2">
                <strong>{{ $message->sender->name }}:</strong>
                <p>{{ $message->message }}</p>
                <div class="text-muted" style="font-size: 0.8em;">
                    {{ $message->created_at->format('d M Y, H:i') }}
                </div>
            </div>
        @endforeach
    </div>

    <!-- Send Message Form -->
    <form action="{{ route('message.store', $chatRoom->id) }}" method="POST">
        @csrf
        <div class="form-group">
            <textarea id="message" name="message" class="form-control" rows="3" placeholder="Type your message here..." required></textarea>
        </div>
        <button type="submit" class="btn btn-primary float-right">Send</button>
    </form>
</div>
@endsection
