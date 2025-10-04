@extends('layouts.app')

@section('styles')
<style>


body.dark h2 {
    color:#fff;
}

body {
    font-family: 'Helvetica Neue', sans-serif;
    background: var(--background);
    color: var(--text-color);
}

h1, h2, h3, h4, h5, h6, .modal-title, .card-title {
    color: var(--text-color);
}

.container {
    padding: 20px;
}

.card {
    margin-bottom: 20px;
    background: var(--modal-bg);
    color: var(--text-color);
    border: 1px solid var(--input-border);
}

/* --- 🎯 Button Styling --- */
.btns {
    padding: 8px 15px;
    color: var(--text-color) !important;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    background: var(--accent);
    border:2px solid #333;
    margin-top:20px;
    font-size:1rem;
}

.btn:hover {
    background: #0056b3;
}

/* --- 📋 Modal Styling --- */
.modal-content {
    background: var(--modal-bg);
    color: var(--text-color);
    margin: 15% auto;
    padding: 20px;
    width: 90%;
    max-width: 500px;
    border-radius: 8px;
    position: relative;
    border: 1px solid var(--input-border);
}
.modal-content textarea {
    width: 100%;
    padding: 10px;
    font-size: 1rem;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    margin-bottom: 0.75rem;
    background-color: #fff;
    color: #111827;
}

/* .modal-footer {
    border-top: 1px solid var(--input-border);
} */

.close-btn {
    color: var(--text-color);
    font-size: 24px;
    cursor: pointer;
    border: none;
    background: transparent;
    opacity: 0.7;
    transition: opacity 0.2s;
    line-height: 1;
}

.close-btn:hover {
    opacity: 1;
}

/* --- 📥 Form Control & Readability --- */
.form-group label {
    color: var(--text-color);
    display: block;
    margin-bottom: 5px;
}

.form-control {
    width: 100%;
    padding: 10px;
    border: 1px solid var(--input-border);
    border-radius: 4px;
    background-color: var(--background);
    color: var(--input-text-color);
    font-size: 16px;
}

.form-control:focus {
    outline: 2px solid var(--accent);
    border-color: var(--accent);
}


.btn-go-live {
    background:#28a745;
    border:none;
    border-radius:3px;
}

.btn-go-live:hover {
    background: #1e7e34;
}

.btn-secondary {
    background:red;
    border:0;
    border-radius: 3px;
}

.btn-secondary:hover {
    background: #c82333;
}

/* --- 📱 Small Screen Responsiveness Tweak --- */
@media (max-width: 768px) {
    .col-md-4 {
        flex: 0 0 100%;
        max-width: 100%;
    }
}

.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1050;
}
.modal-body .form-group textarea{
    width:90%;
    padding: 10px;
    height:15vh;
    font-size: 1rem;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    margin-bottom: 0.75rem;
}
.modal.show {
    display: block;
}
</style>
@endsection

@section('content')
<div class="container">
    <h2>Live Streams</h2>
    @if(auth()->check())
        <button type="button" class="btns btn-primary" data-bs-toggle="modal" data-bs-target="#startStreamModal">
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
                <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">&times;</button>
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
                    <button type="button" class="btns btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btns btn-go-live">Go Live</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const startStreamButton = document.querySelector('[data-bs-target="#startStreamModal"]');
    const modal = document.getElementById('startStreamModal');

    function showModal(event) {
        if (event) event.preventDefault();
        if (modal) {
            modal.classList.add('show');
            modal.style.display = 'block';
        }
    }

    function hideModal() {
        if (modal) {
            modal.classList.remove('show');
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
        }
    }

    document.querySelectorAll('[data-bs-target="#startStreamModal"]').forEach(button => {
        button.removeEventListener('click', showModal);
        button.addEventListener('click', showModal);
    });

    document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(button => {
        button.removeEventListener('click', hideModal);
        button.addEventListener('click', hideModal);
    });

    if (modal) {
        modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                hideModal();
            }
        });
    }
</script>
@endsection
