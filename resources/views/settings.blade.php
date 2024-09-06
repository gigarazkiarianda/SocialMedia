@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Pengaturan</div>

                <div class="card-body">
                    <!-- Tombol Ganti Password -->
                    <a href="#" class="btn btn-outline-primary w-100 d-flex align-items-center">
                        <i class="bi bi-lock" style="font-size: 1.5rem; margin-right: 10px;"></i>
                        Ganti Password
                    </a>

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
