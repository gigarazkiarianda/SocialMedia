@extends('layouts.app')

@section('content')
<div class="container">

    <!-- Display username -->
    <h3>Welcome, {{ Auth::user()->name }}!</h3>

    @if($biodata)
        <div class="card mb-3">
            <div class="card-body">
                <h4 class="card-title">{{ $biodata->full_name }}</h4>
                <p class="card-text"><strong>Birth Date:</strong> {{ $biodata->birth_date }}</p>
                <p class="card-text"><strong>Birth Place:</strong> {{ $biodata->birth_place }}</p>
                @if($biodata->photo)
                    <p><img src="{{ asset('storage/' . $biodata->photo) }}" alt="Photo" width="150"></p>
                @else
                    <p>No photo available.</p>
                @endif
                <a href="{{ route('biodata.edit', $biodata->id) }}" class="btn btn-primary">Edit Biodata</a>
            </div>
        </div>
    @else
        <p>No biodata available. <a href="{{ route('biodata.create') }}">Create now</a>.</p>
    @endif
</div>
@endsection
