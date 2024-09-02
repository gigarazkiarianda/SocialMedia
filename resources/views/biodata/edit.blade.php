@extends('layouts.app')

@section('content')
<div class="container">
    <form method="POST" action="{{ route('biodata.update', $biodata->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="full_name">Full Name</label>
            <input type="text" class="form-control" id="full_name" name="full_name" value="{{ old('full_name', $biodata->full_name) }}" required>
            @error('full_name')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="birth_date">Birth Date</label>
            <input type="date" class="form-control" id="birth_date" name="birth_date" value="{{ old('birth_date', $biodata->birth_date) }}" required>
            @error('birth_date')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="birth_place">Birth Place</label>
            <input type="text" class="form-control" id="birth_place" name="birth_place" value="{{ old('birth_place', $biodata->birth_place) }}" required>
            @error('birth_place')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="photo">Photo</label>
            <input type="file" class="form-control-file" id="photo" name="photo">
            @if($biodata->photo)
                <p>Current Photo: <img src="{{ asset('storage/' . $biodata->photo) }}" alt="Photo" width="100"></p>
            @endif
            @error('photo')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
