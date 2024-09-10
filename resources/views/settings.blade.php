@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Pengaturan</div>

                <div class="card-body">
                    <!-- Tombol Ganti Password -->
                    <a href="{{ route('password.change') }}" class="btn btn-outline-primary w-100 d-flex align-items-center">
                        <i class="bi bi-lock" style="font-size: 1.5rem; margin-right: 10px;"></i>
                        Ganti Password
                    </a>

                    <!-- Tombol Hide Posting -->
                    <a href="{{ route('hide.posts') }}" class="btn btn-outline-info w-100 d-flex align-items-center mt-3">
                        <i class="bi bi-eye-slash" style="font-size: 1.5rem; margin-right: 10px;"></i>
                        Hide Posting
                    </a>

                    <!-- Tombol Archive -->
                    <button class="btn btn-outline-warning w-100 d-flex align-items-center mt-3">
                        <i class="bi bi-archive" style="font-size: 1.5rem; margin-right: 10px;"></i>
                        Archive
                    </button>

                    <!-- Tombol Logout -->
                    <form method="POST" action="{{ route('logout') }}" class="mt-3">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary w-100 d-flex align-items-center">
                            <i class="bi bi-power" style="font-size: 1.5rem; margin-right: 10px;"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
