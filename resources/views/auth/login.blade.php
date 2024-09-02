@extends('layouts.app')

@section('content')
<div class="container">
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>

        <!-- Tambahkan tombol Register di bawah tombol Login -->
        <div class="mt-3">
            <a href="{{ route('register') }}" class="btn btn-secondary">Register</a>
        </div>
    </form>
</div>
@endsection
