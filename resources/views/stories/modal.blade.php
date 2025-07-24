<div class="story-modal">
    @foreach ($stories as $story)
        <div class="story-slide">
            @if ($story->media_type === 'image')
                <img src="{{ asset('storage/' . $story->media_path) }}" alt="Story image">
            @elseif ($story->media_type === 'video')
                <video controls>
                    <source src="{{ asset('storage/' . $story->media_path) }}" type="video/mp4">
                </video>
            @elseif ($story->media_type === 'text')
                <div class="story-text" style="background-color: {{ $story->background_color }}">
                    <p>{{ $story->text }}</p>
                </div>
            @endif
        </div>
    @endforeach
</div>
