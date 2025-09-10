@extends('layouts.app')
<html lang="en">
<head>
    @include('layouts.head')
</head>
<body>
<div class="d-flex">
    @section('content')
    <div class="content-wrapper">
        <nav class="navbar">
            <div class="containers">
                <div class="logo">
                    <a class="navbar-brand" href="{{ route('marketshowroom') }}">
                        <h3>Marketplace</h3>
                    </a>
                </div>
                <div class="d-flex mx-auto search-bar">
                    <input class="form-control" type="search" placeholder="Search by category" aria-label="Search">
                </div>
                <div class="d-flex align-items-center">
                    <a href="{{ url('/inbox') }}" class="me-4 inbox-icon">
                        <i class="bi bi-inbox-fill"></i>
                    </a>

                </div>
            </div>
            <div class="create-listing-container">
                <a href="{{ route('marketplace.newlisting') }}" class="create-listing">+ Create new listing</a>
            </div>
        </nav>

        <div class="main-container">
            <div class="form-container">
            <header class="form-header">
                <h1>Item for sale</h1>
            </header>

            <div class="seller-info">
            @if(auth()->user()->profile_picture)
                <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" class="profile-pic" alt="Profile Picture">
            @else
                <i class="bi bi-person-circle profile-icon"></i>
            @endif
                <div class="seller-details">
                    <span class="seller-name"><a href="{{ route('user.profile',$user->id) }}">{{$user->username}}</a></span>
                    <span class="listing-details">Listing to Marketplace · <span class="public-icon">🌍</span> Public</span>
                </div>
            </div>

            <form action="{{ route('marketplace.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="photo-section">
                    <label for="new-listing-photo-upload" class="photo-upload-box">
                        <div class="upload-icon-wrapper">
                            <span class="upload-icon">📸</span>
                        </div>
                        <p class="add-photos-text">Add photos</p>
                        <p class="drag-drop-text">or drag and drop</p>
                        <input type="file" id="new-listing-photo-upload" name="photos[]" multiple accept="image/*" class="new-listing-photo-input" maxlength="10">
                    </label>
                    <p class="photo-count">Photos · 0/10 - You can add up to 10 photos.</p>

                    <div id="photo-preview-container" class="photo-preview-container"></div>
                </div>

                <section class="required-fields">
                    <h2>Required</h2>
                    <p class="required-description">Be as descriptive as possible.</p>
                    <input type="text" placeholder="Title" class="title-input" name="title">
                    <input type="text" placeholder="Price" class="price-input" name="price">
                    <select class="category-select" name="category">
                        <option value="" disabled selected>Category</option>
                        <option value="phones">Phones</option>
                        <option value="vehicles">Vehicles</option>
                        <option value="clothing">Clothing</option>
                        <option value="games">Games</option>
                        <option value="electronics">Electronics</option>
                        <option value="african-crafts">African Crafts</option>
                        <option value="african-textiles">African Textiles</option>
                        <option value="jewelry">Jewelry</option>
                        <option value="home-decor">Home Decor</option>
                        <option value="furniture">Furniture</option>
                        <option value="books">Books</option>
                        <option value="sports">Sports Equipment</option>
                        <option value="beauty">Beauty Products</option>
                        <option value="toys">Toys</option>
                        <option value="appliances">Appliances</option>
                        <option value="music">Musical Instruments</option>
                        <option value="art">Art & Collectibles</option>
                        <option value="pets">Pet Supplies</option>
                        <option value="food">Food & Beverages</option>
                        <option value="african-spices">African Spices</option>
                        <option value="tools">Tools & Hardware</option>
                        <option value="baby">Baby Products</option>
                        <option value="health">Health & Wellness</option>
                        <option value="garden">Garden & Outdoor</option>
                        <option value="african-beadwork">African Beadwork</option>
                    </select>
                    <select class="condition-select" name="condition">
                        <option value="" disabled selected>Condition</option>
                        <option value="new">New</option>
                        <option value="like new">Like New</option>
                        <option value="good">Good</option>
                        <option value="fair">Fair</option>
                        <option value="poor">Poor</option>
                        <option value="slightly use">slightly use</option>
                    </select>
                </section>

                <section class="more-details">
                    <textarea placeholder="Description" class="description-textarea" name="description"></textarea>
                    <div class="location-input">
                        <span class="location-icon">📍</span>
                        <input type="text" placeholder="Location" name="location">
                    </div>
                </section>

                <button type="submit" class="add-item-btn">Add item to the market</button>
            </form>
        </div>
        </div>
    </div>
    @endsection
</div>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('New Listing Photo Script Loaded!');

        const photoInput = document.querySelector('.new-listing-photo-input');
        const photoCountText = document.querySelector('.photo-count');
        const photoPreviewContainer = document.getElementById('photo-preview-container');

        let selectedFiles = [];

        function updatePhotoCount() {
            photoCountText.textContent = `Photos · ${selectedFiles.length}/10 - You can add up to 10 photos.`;
            console.log('Photo count updated to:', selectedFiles.length);
        }

        function renderPreviews() {
            photoPreviewContainer.innerHTML = '';
            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const previewDiv = document.createElement('div');
                    previewDiv.classList.add('photo-preview-item');
                    previewDiv.innerHTML = `
                        <img src="${e.target.result}" alt="Preview" class="preview-image">
                        <span class="close-btn" data-index="${index}">&times;</span>
                    `;
                    photoPreviewContainer.appendChild(previewDiv);
                };
                reader.readAsDataURL(file);
            });
            console.log('Preview images rendered.');
        }

        function removeFile(index) {
            selectedFiles.splice(index, 1);

            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => {
                dataTransfer.items.add(file);
            });
            photoInput.files = dataTransfer.files;

            renderPreviews();
            updatePhotoCount();
        }

        photoInput.addEventListener('change', function(e) {
            console.log('New Listing File selection changed!', e.target.files);
            const newFiles = Array.from(e.target.files);
            selectedFiles = selectedFiles.concat(newFiles).slice(0, 10);

            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => {
                dataTransfer.items.add(file);
            });
            photoInput.files = dataTransfer.files;

            renderPreviews();
            updatePhotoCount();
        });

        photoPreviewContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('close-btn')) {
                const index = e.target.getAttribute('data-index');
                removeFile(index);
            }
        });
    });
</script>
@endpush
</body>
</html>
