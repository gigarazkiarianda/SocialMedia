@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $user->name }}</h1>
    <p><strong>Email:</strong> {{ $user->email }}</p>

    @if($biodata)
        <div class="card mt-4">
            <div class="card-body">
                <p><strong>Username:</strong> {{ $user->name }}</p>
                <p><strong>Birth Date:</strong> {{ $biodata->birth_date }}</p>
                <p><strong>Birth Place:</strong> {{ $biodata->birth_place }}</p>
                @if($biodata->photo)
                    <p><img src="{{ asset('storage/' . $biodata->photo) }}" alt="Photo" class="img-fluid" width="150"></p>
                @else
                    <p>No photo available.</p>
                @endif
            </div>
        </div>
    @else
        <p>No additional biodata available.</p>
    @endif
</div>
@endsection
