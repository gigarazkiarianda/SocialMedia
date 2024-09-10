@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12 col-lg-10">
            <div class="card">
                <div class="card-header">Postingan yang Di-hide</div>

                <div class="card-body">
                    @if($hiddenPosts->isEmpty())
                        <p>Tidak ada postingan yang di-hide.</p>
                    @else
                        @foreach($hiddenPosts as $post)
                            <div class="card mb-3">
                                <!-- Gambar Postingan -->
                                <div class="position-relative">
                                    <img src="{{ asset('storage/' . $post->image) }}" class="card-img-top" alt="Gambar Postingan" style="object-fit: cover; width: 100%; height: 250px;">
                                </div>

                                <div class="card-body">
                                    <!-- Judul dan Konten -->
                                    <h5 class="card-title">{{ $post->title }}</h5>
                                    <p class="card-text">{{ $post->content }}</p>

                                    <!-- Tombol Tampilkan Postingan -->
                                    <form action="{{ route('post.unhide', $post->id) }}" method="POST" class="d-inline-block w-100">
                                        @csrf
                                        <button type="submit" class="btn btn-secondary btn-block">Tampilkan Post</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            modal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const postId = button.getAttribute('data-post-id');
                const formAction = `{{ url('/posts') }}/${postId}/unhide`;

                const form = modal.querySelector('#restoreForm');
                form.action = formAction;
            });
        });
    });
</script>
@endpush
