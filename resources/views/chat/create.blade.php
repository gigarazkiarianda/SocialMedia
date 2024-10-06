@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create New Chat Room</h1>

    <form action="{{ route('chat.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="user_id">Select User:</label>
            <select name="user_id" id="user_id" class="form-control">
                @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="message">Message:</label>
            <textarea name="message" id="message" rows="5" class="form-control" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Send Message</button>
    </form>
</div>
@endsection
