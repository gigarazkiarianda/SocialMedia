

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Postingan</h1>

    <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="title">Judul</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $post->title) }}" required>
        </div>

        <div class="form-group">
            <label for="content">Konten</label>
            <textarea name="content" class="form-control" rows="5" required>{{ old('content', $post->content) }}</textarea>
        </div>

        <div class="form-group">
            <label for="image">Gambar (Opsional)</label>
            <input type="file" name="image" class="form-control-file">
            @if($post->image)
                <img src="{{ Storage::url($post->image) }}" alt="Current Image" width="150">
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>
</div>
@endsection
