@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex align-items-center mb-4">
        @php
            $otherUser = $chatRoom->user1->id === Auth::id() ? $chatRoom->user2 : $chatRoom->user1;
        @endphp
        @if($otherUser->biodata && $otherUser->biodata->photo)
            <img src="{{ asset('storage/' . $otherUser->biodata->photo) }}" alt="Foto Profil" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
        @else
            <img src="https://via.placeholder.com/150" alt="Foto Default" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
        @endif

        <div class="ml-3">
            <h3>{{ $otherUser->name }}</h3>
        </div>
    </div>

    <!-- Kontainer Chat -->
    <div class="chat-container" style="position: relative; height: calc(100vh - 160px);">
        <!-- Pesan Chat -->
        <div class="chat-messages" style="height: calc(100% - 50px); overflow-y: auto; padding: 10px; border: 1px solid #ddd; border-radius: 5px; background: #f9f9f9; padding-bottom: 60px;">
            @php
                $lastDisplayedDate = null;
            @endphp

            @foreach($chatRoom->messages as $message)
                @php
                    $utcDate = $message->created_at->format('Y-m-d');
                    $localDate = \Carbon\Carbon::parse($message->created_at)->setTimezone('Asia/Jakarta')->format('d F Y');
                @endphp

                @if($lastDisplayedDate !== $localDate)
                    <!-- Tampilkan tanggal sebelumnya jika berbeda dari lastDisplayedDate -->
                    @if($lastDisplayedDate !== null)
                        <div class="text-center mb-2" style="margin: 10px 0; font-size: 0.75rem; color: gray;">
                            <h5>{{ $lastDisplayedDate }}</h5>
                        </div>
                    @endif
                    @php
                        $lastDisplayedDate = $localDate;
                    @endphp
                @endif

                <!-- Tampilkan pesan -->
                <div class="message mb-1 d-flex {{ $message->sender->id === Auth::id() ? 'justify-content-end' : 'justify-content-start' }}" style="margin-bottom: 5px;">
                    <div class="message-box p-2 rounded {{ $message->sender->id === Auth::id() ? 'bg-primary text-white' : 'bg-secondary text-white' }}" style="max-width: 75%; word-wrap: break-word;">
                        <p class="mb-1">{{ $message->message }}</p>
                        <div class="text-center" style="font-size: 0.8em; color: white;">
                            {{ \Carbon\Carbon::parse($message->created_at)->setTimezone('Asia/Jakarta')->format('H:i') }}
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Tampilkan tanggal terakhir jika berbeda dari lastDisplayedDate -->
            @if($lastDisplayedDate !== null)
                <div class="text-center mb-2" style="margin: 10px 0; font-size: 0.75rem; color: gray;">
                    <h5>{{ $lastDisplayedDate }}</h5>
                </div>
            @endif
        </div>

        <!-- Form Kirim Pesan -->
        <form action="{{ route('message.store', $chatRoom->id) }}" method="POST" class="chat-form" style="position: absolute; bottom: 0; left: 0; right: 0; background: #fff; padding: 10px; border-top: 1px solid #ddd; z-index: 10; display: flex; align-items: center;">
            @csrf
            <div class="form-group mb-0 flex-grow-1">
                <textarea id="message" name="message" class="form-control" rows="2" placeholder="Ketik pesan Anda di sini..." required style="resize: none;"></textarea>
            </div>
            <button type="submit" class="btn btn-primary ml-2">Kirim</button>
        </form>
    </div>
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

<!-- Navigasi Bawah -->
<div class="bottom-nav" style="position: fixed; bottom: 0; left: 0; right: 0; background: #fff; border-top: 1px solid #ddd; z-index: 100; display: flex; justify-content: space-around; padding: 10px 0;">
    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}" style="flex: 1; text-align: center;">
        <i class="fas fa-home"></i>
    </a>
    <a href="{{ route('chat.index') }}" class="{{ request()->routeIs('chat.index') ? 'active' : '' }}" style="flex: 1; text-align: center;">
        <i class="fas fa-comments"></i>
    </a>
    <a href="{{ route('post.create') }}" class="{{ request()->routeIs('post.create') ? 'active' : '' }}" style="flex: 1; text-align: center;">
        <i class="fas fa-plus"></i>
    </a>
    <a href="{{ route('user.myprofile') }}" class="{{ request()->routeIs('user.myprofile') ? 'active' : '' }}" style="flex: 1; text-align: center;">
        <i class="fas fa-user"></i>
    </a>
</div>
@endpush
