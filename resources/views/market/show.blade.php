<div class="main-contents">
    <div class="image-gallery-container">
        <div class="main-image-wrapper">
            @if($item->photos->count() > 0)
                <button class="prev-arrow" onclick="changeImage(-1)">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <img id="main-image" src="{{ asset('storage/' . $item->photos->first()->path) }}" alt="{{ $item->title }}" class="main-image">
                <button class="next-arrow" onclick="changeImage(1)">
                    <i class="bi bi-chevron-right"></i>
                </button>
            @else
                <div class="placeholder-image-main">No Image</div>
            @endif
        </div>

        <div class="thumbnail-gallery">
            @foreach($item->photos as $index => $photo)
                <img src="{{ asset('storage/' . $photo->path) }}" alt="Thumbnail {{ $index + 1 }}" class="thumbnail-image" onclick="goToImage({{ $index }})">
            @endforeach
        </div>
    </div>

    <div class="details-section">
        <div class="details-header">
            <h1 class="item-title">{{ $item->title }}</h1>
            <p class="item-price">GHS{{ number_format($item->price, 2) }}</p>
            <p class="item-listed">Listed {{ \App\Helpers\TimeFormatter::formatDiffForHumansAbbreviated($item->created_at) }} days ago in {{ $item->location }}</p>
        </div>

        <div class="details-body">
            <div class="details-card">
                <h3>Details</h3>
                <p><strong>Condition:</strong> {{ $item->condition }}</p>
                <p><strong>Category:</strong> {{ $item->category }}</p>
                <p><strong>Location:</strong> {{ $item->location }}</p>
            </div>

            <div class="seller-info-card">
                <h3>Seller information</h3>
                @if(Auth::check() && Auth::id() == $item->user->id)
    <p><strong>Seller:</strong> You</p>
@else
    <p><strong>Seller:</strong> {{ $item->user->username }}</p>
@endif
                <p>Joined afroConnect in 2022</p>
            </div>

            <div class="description-card">
                <h3>Description</h3>
                <p>{{ $item->description }}</p>
            </div>
        </div>

        <div class="message-section">
            <textarea placeholder="Send seller a message" class="message-input"></textarea>
            <button class="send-message-btn">Send</button>
        </div>
    </div>
</div>

<script>
    // Initialize image navigation functionality
    function initializeImageNavigation() {
        let currentImageIndex = 0;
        const photos = @json($item->photos);
        const mainImage = document.getElementById('main-image');

        // Hide arrows if only one image
        if (photos.length <= 1) {
            document.querySelectorAll('.prev-arrow, .next-arrow').forEach(btn => btn.style.display = 'none');
        }

        window.changeImage = function(direction) {
            currentImageIndex += direction;
            if (currentImageIndex >= photos.length) {
                currentImageIndex = 0;
            }
            if (currentImageIndex < 0) {
                currentImageIndex = photos.length - 1;
            }
            updateImage();
        };

        window.goToImage = function(index) {
            currentImageIndex = index;
            updateImage();
        };

        function updateImage() {
            if (photos.length > 0 && mainImage) {
                mainImage.src = "{{ asset('storage/') }}/" + photos[currentImageIndex].path;
                mainImage.alt = "{{ $item->title }} - Image " + (currentImageIndex + 1);
            }
        }
    }

    // Run initialization immediately
    initializeImageNavigation();
</script>
