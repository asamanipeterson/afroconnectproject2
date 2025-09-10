@if (!Request::is('user/*') && !Request::is('marketplace') && !Request::is('marketplace/*'))
<aside class="rightbar">
    <div class="who-to-follow">
        <h4>Who to Follow</h4>
        <ul class="follow-list">
            {{-- This is a conceptual loop. You will need to pass the $suggestedUsers variable from your controller. --}}
            @foreach ($suggestedUsers as $user)
            <li class="follow-item">
                <a href="{{ route('user.profile', $user->username) }}" class="user-link">
                    {{-- Display user's profile picture or a placeholder icon --}}
                    @if ($user->profile_picture)
                        <img src="{{ asset('storage/' . $user->profile_picture) }}" class="avatar" alt="{{ $user->name }}">
                    @else
                        <i class="bi bi-person-circle avatar"></i>
                    @endif
                    <span class="user-name">{{ $user->username }}</span>
                </a>

                {{-- Your provided follow button logic --}}
                @if(Auth::check() && Auth::id() !== $user->id)
                    <button class="follow-btn {{ Auth::user()->isFollowing($user) ? 'following' : '' }}" data-user-id="{{ $user->id }}">
                        {{ Auth::user()->isFollowing($user) ? 'Following' : 'Follow' }}
                    </button>
                @endif
            </li>
            @endforeach

            {{-- <li class="view-all"><a href="{{ route('users.suggestions') }}">View All Suggestions</a></li> --}}
        </ul>
    </div>
</aside>
@endif
