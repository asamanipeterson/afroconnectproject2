@extends('layouts.app')

@section('styles')
<style>
    .container { padding: 20px; }
    .card { margin-bottom: 20px; }
    .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1050; }
    .modal.show { display: block; }
    .modal-content { background: white; margin: 15% auto; padding: 20px; width: 80%; max-width: 500px; border-radius: 8px; position: relative; }
    .close-btn { float: right; font-size: 20px; cursor: pointer; border: none; background: transparent; }
    .form-group { margin-bottom: 15px; }
    .form-control { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
    .btn { padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
    .btn:hover { background: #0056b3; }
</style>
@endsection

@section('content')
<div class="container">
    <h2>Live Streams</h2>
    @if(auth()->check())
        <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#startStreamModal">
            Start Live Stream
        </button>
    @endif
    <div class="row">
        @foreach($streams as $stream)
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $stream->title }}</h5>
                        <p class="card-text">By {{ $stream->user->username }}</p>
                        <a href="{{ route('live.show', $stream) }}" class="btn btn-primary">Watch</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="modal fade" id="startStreamModal" tabindex="-1" aria-labelledby="startStreamModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="startStreamModalLabel">Start Live Stream</h5>
                <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">×</button>
            </div>
            <form action="{{ route('live.start') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="title">Stream Title</label>
                        <input type="text" id="title" name="title" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Go Live</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script section is empty as the modal should be handled by Bootstrap JS --}}
@endsection
