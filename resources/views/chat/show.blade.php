@extends('layouts.app')

@section('content')
<div class="container">

    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ url()->previous() }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="d-flex align-items-center mb-4">
        @php
            $otherUser = $chatRoom->user1->id === Auth::id() ? $chatRoom->user2 : $chatRoom->user1;
        @endphp
        @if($otherUser->biodata && $otherUser->biodata->photo)
            <img src="{{ asset('storage/' . $otherUser->biodata->photo) }}" alt="Profile Photo" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
        @else
            <img src="https://via.placeholder.com/150" alt="Default Photo" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
        @endif

        <div class="ml-3">
            <h3>{{ $otherUser->name }}</h3>
        </div>
    </div>

    <!-- Chat Container -->
    <div class="chat-container mb-4" style="position: relative; height: 400px; overflow: hidden;">
        <!-- Chat Messages -->
        <div class="chat-messages" style="height: calc(100% - 40px); overflow-y: scroll; padding: 10px; border: 1px solid #ddd; border-radius: 5px; background: #f9f9f9;">
            <!-- Date -->
            <div class="text-center mb-2" style="margin: 10px 0; font-size: 0.75rem; color: gray;">
                <h5>{{ now()->format('d M Y') }}</h5>
            </div>

            @foreach($chatRoom->messages as $message)
                <div class="message mb-1 d-flex {{ $message->sender->id === Auth::id() ? 'justify-content-end' : 'justify-content-start' }}" style="margin-bottom: 5px;">
                    <div class="message-box p-2 rounded {{ $message->sender->id === Auth::id() ? 'bg-primary text-white' : 'bg-secondary text-white' }}" style="max-width: 75%; word-wrap: break-word;">
                        <p class="mb-1">{{ $message->message }}</p>
                        <div class="text-center" style="font-size: 0.8em; color: white;">
                            {{ $message->created_at->format('H:i') }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Send Message Form -->
    <form action="{{ route('message.store', $chatRoom->id) }}" method="POST" class="mb-4">
        @csrf
        <div class="form-group">
            <textarea id="message" name="message" class="form-control" rows="3" placeholder="Type your message here..." required style="resize: none;"></textarea>
        </div>
        <button type="submit" class="btn btn-primary float-right">Send</button>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function(){
        // Auto scroll to the bottom of the chat messages
        const chatMessages = $('.chat-messages');
        chatMessages.scrollTop(chatMessages[0].scrollHeight);
    });
</script>

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
@endpush
