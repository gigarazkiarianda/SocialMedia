@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex align-items-center mb-4">
        @if(Auth::user()->biodata && Auth::user()->biodata->photo)
            <img src="{{ asset('storage/' . Auth::user()->biodata->photo) }}" alt="Profile Photo" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
        @else
            <img src="https://via.placeholder.com/150" alt="Default Photo" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
        @endif

        <div class="ml-4">
            <h1>Welcome, {{ Auth::user()->name }}!</h1>
            <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
        </div>
    </div>

    <!-- Display number of followers and following -->
    <div class="row mb-4">
        <div class="col-md-6 text-center">
            <h4>Followers</h4>
            <p>{{ Auth::user()->followers->count() }}</p>
        </div>
        <div class="col-md-6 text-center">
            <h4>Following</h4>
            <p>{{ Auth::user()->following->count() }}</p>
        </div>
    </div>

    @if($biodata)
        <div class="card mb-3">
            <div class="card-body">
                <h4 class="card-title">{{ $biodata->full_name }}</h4>
                <p class="card-text"><strong>Birth Date:</strong> {{ $biodata->birth_date }}</p>
                <p class="card-text"><strong>Birth Place:</strong> {{ $biodata->birth_place }}</p>
                <a href="{{ route('biodata.edit', $biodata->id) }}" class="btn btn-primary">Edit Biodata</a>
            </div>
        </div>
    @else
        <p>No biodata available. <a href="{{ route('biodata.create') }}">Create now</a>.</p>
    @endif
</div>
@endsection
