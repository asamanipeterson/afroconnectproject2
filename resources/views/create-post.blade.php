{{-- @extends('layouts.app')

@section('title', 'Create New Content')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/create-post.css') }}">
<style>
    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        background: rgba(0, 0, 0, 0.7);
        display: none;
        z-index: 40;
    }
    .fade-in {
        animation: fadeIn 0.5s ease;
    }
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endsection

@section('content')
<button id="open-create-form" class="create-post-btn">Create New Content</button>

<div id="overlay" class="overlay"></div>

<div id="create-form-container" class="container hidden fade-in">
    <div class="flex mb-6 border-b mr-8">
        <button id="post-tab" class="tab-button active px-6 py-2 text-lg font-semibold focus:outline-none mr-8">
            Create Post
        </button>
        <button id="story-tab" class="tab-button px-6 py-2 text-lg font-semibold focus:outline-none">
            Create Story
        </button>
        <button id="close-create-form" class="ml-auto text-red-500 font-bold">X</button>
    </div>


    <div id="post-form" class="form-container fade-in">
        <h1 class="text-2xl font-bold mb-4">Create New Post</h1>
        <form action="{{ route('create-post.submit') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="caption">Caption:</label>
                <textarea name="caption" rows="3">{{ old('caption') }}</textarea>
            </div>
            <div id="media-inputs-container">
                <div class="media-item p-4 border rounded mb-3 bg-gray-50" data-index="0">
                    <label>Media (Image/Video/Audio):</label>
                    <input type="file" name="media_files[0]">
                    <label>Or Text Content:</label>
                    <textarea name="text_contents[0]" rows="2">{{ old('text_contents.0') }}</textarea>
                    <label>Optional Sound:</label>
                    <input type="file" name="sound_files[0]">
                    <button type="button" class="remove-media-item hidden">Remove</button>
                </div>
            </div>
            <button type="button" id="add-media-item" class="add-media-item">Add Media Item</button>
            <span id="media-count">1/20</span>
            <div class="form-submit-container">
                <button type="submit" class="create-post-btn">Create Post</button>
            </div>
        </form>
    </div>


    <div id="story-form" class="form-container hidden fade-in">
        <h1 class="text-2xl font-bold mb-4">Create New Story</h1>

        <form action="" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label>Caption (Optional):</label>
                <textarea name="caption" rows="2">{{ old('caption') }}</textarea>
            </div>
            <div class="mb-4">
                <label>Story Type:</label><br>
                <input type="radio" name="story_type" value="image" checked> Image
                <input type="radio" name="story_type" value="video"> Video
                <input type="radio" name="story_type" value="text"> Text
            </div>
            <div id="story-media" class="mb-4">
                <label>Upload Media:</label>
                <input type="file" name="media">
            </div>
            <div id="story-text" class="mb-4 hidden">
                <label>Story Text:</label>
                <textarea name="text_content" rows="4"></textarea>
            </div>
            <div class="mb-4">
                <label>Background Color (text only):</label>
                <input type="color" name="background" value="#3B82F6">
            </div>
            <div class="mb-4">
                <label>Duration:</label>
                <select name="duration">
                    <option value="5">5s</option>
                    <option value="7">7s</option>
                    <option value="10">10s</option>
                    <option value="15">15s</option>
                </select>
            </div>
            <div class="form-submit-container">
                <button type="submit" class="create-post-btn">Create Story</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const openBtn = document.getElementById('open-create-form');
    const closeBtn = document.getElementById('close-create-form');
    const overlay = document.getElementById('overlay');
    const formContainer = document.getElementById('create-form-container');

    openBtn.addEventListener('click', function () {
        overlay.style.display = 'block';
        formContainer.classList.remove('hidden');
    });

    closeBtn.addEventListener('click', function () {
        overlay.style.display = 'none';
        formContainer.classList.add('hidden');
    });

    overlay.addEventListener('click', function () {
        overlay.style.display = 'none';
        formContainer.classList.add('hidden');
    });

    const postTab = document.getElementById('post-tab');
    const storyTab = document.getElementById('story-tab');
    const postForm = document.getElementById('post-form');
    const storyForm = document.getElementById('story-form');

    postTab.addEventListener('click', function () {
        postTab.classList.add('active');
        storyTab.classList.remove('active');
        postForm.classList.remove('hidden');
        storyForm.classList.add('hidden');
    });

    storyTab.addEventListener('click', function () {
        storyTab.classList.add('active');
        postTab.classList.remove('active');
        storyForm.classList.remove('hidden');
        postForm.classList.add('hidden');
    });


    const addBtn = document.getElementById('add-media-item');
    const mediaContainer = document.getElementById('media-inputs-container');
    const mediaCount = document.getElementById('media-count');
    let index = 1;

    addBtn.addEventListener('click', function () {
        if (index >= 20) return;
        const div = document.createElement('div');
        div.className = 'media-item p-4 border rounded mb-3 bg-gray-50';
        div.innerHTML = `
            <label>Media (Image/Video/Audio):</label>
            <input type="file" name="media_files[${index}]">
            <label>Or Text Content:</label>
            <textarea name="text_contents[${index}]" rows="2"></textarea>
            <label>Optional Sound:</label>
            <input type="file" name="sound_files[${index}]">
            <button type="button" class="remove-media-item">Remove</button>
        `;
        const removeBtn = div.querySelector('.remove-media-item');
        removeBtn.addEventListener('click', function () {
            div.remove();
            index--;
            mediaCount.innerText = `${index}/20`;
        });
        mediaContainer.appendChild(div);
        index++;
        mediaCount.innerText = `${index}/20`;
    });


    const storyType = document.querySelectorAll('input[name="story_type"]');
    const storyMedia = document.getElementById('story-media');
    const storyText = document.getElementById('story-text');

    storyType.forEach(radio => {
        radio.addEventListener('change', function () {
            if (this.value === 'text') {
                storyMedia.classList.add('hidden');
                storyText.classList.remove('hidden');
            } else {
                storyMedia.classList.remove('hidden');
                storyText.classList.add('hidden');
            }
        });
    });
});
</script>
@endsection --}}
