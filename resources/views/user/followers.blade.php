@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $user->name }}'s Followers</h1>
    <div class="row">
        @forelse($followers as $follower)
            <div class="col-12 col-sm-6 col-md-4 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        @if($follower->biodata && $follower->biodata->photo)
                            <img src="{{ asset('storage/' . $follower->biodata->photo) }}" alt="Follower Photo" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                        @else
                            <img src="https://via.placeholder.com/100" alt="Default Photo" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                        @endif
                        <h5 class="card-title mt-2">{{ $follower->name }}</h5>
                        <p class="card-text"><strong>Email:</strong> {{ $follower->email }}</p>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center">No followers found.</p>
        @endforelse
    </div>
</div>
@endsection
