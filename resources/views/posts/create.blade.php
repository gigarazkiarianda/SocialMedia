@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Buat Postingan Baru</h2>

    <form action="{{ route('post.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="title">Judul</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>

        <div class="form-group">
            <label for="content">Konten</label>
            <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
        </div>

        <div class="form-group">
            <label for="image">Gambar</label>
            <input type="file" class="form-control-file" id="image" name="image" accept="image/*">
        </div>

        <div class="form-group">
            <label for="crop-ratio">Rasio Crop</label>
            <select id="crop-ratio" class="form-control">
                <option value="1">1:1</option>
                <option value="16:9">16:9</option>
                <option value="9:16">9:16</option>
            </select>
        </div>

        <div class="form-group">
            <img id="image-preview" src="#" alt="Image Preview" style="display: none; max-width: 100%;">
        </div>

        <button type="submit" class="btn btn-primary">Buat Postingan</button>
    </form>
</div>

<!-- Bottom Navigation Bar -->
<div class="bottom-nav">
    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="fas fa-home"></i>
    </a>
    <a href="{{ route('chat.index') }}" class="{{ request()->routeIs('chat.index') ? 'active' : '' }}">
        <i class="fas fa-comments"></i>
    </a>
    <a href="{{ route('post.create') }}" class="{{ request()->routeIs('post.create') ? 'active' : '' }}">
        <i class="fas fa-plus"></i>
    </a>
    <a href="{{ route('user.myprofile') }}" class="{{ request()->routeIs('user.myprofile') ? 'active' : '' }}">
        <i class="fas fa-user"></i>
    </a>
</div>
@endsection

@push('styles')
<style>
    /* Cropper.js style adjustments */
    #image-preview {
        max-height: 400px; /* Sesuaikan sesuai kebutuhan */
    }
</style>
@endpush

@push('scripts')
<script>
    let cropper;

    document.getElementById('image').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const imagePreview = document.getElementById('image-preview');
                imagePreview.src = e.target.result;
                imagePreview.style.display = 'block';

                if (cropper) {
                    cropper.destroy();
                }

                cropper = new Cropper(imagePreview, {
                    aspectRatio: 1,
                    viewMode: 1,
                    autoCropArea: 1,
                    responsive: true,
                });

                document.getElementById('crop-ratio').addEventListener('change', function() {
                    const ratio = this.value.split(':').map(Number);
                    cropper.setAspectRatio(ratio[0] / ratio[1]);
                });
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
