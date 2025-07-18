@if (!Request::is('user/*'))
<aside class="rightbar">
    <div class="messages">
        <div class="messages-header">
            <h4>Messages</h4>
            <div class="search-container">
                <input type="text" class="search" placeholder="Search...">
            </div>
            <div class="tabs">
                <span class="tab active">Primary</span>
                <span class="tab">General</span>
                <span class="tab">Requests(4)</span>
            </div>
        </div>
        <ul class="message-list">
            <li>@if(auth()->user()->profile_picture) <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" class="avatar" alt="Roger Korsgaard"> @else <i class="bi bi-person-circle avatar"></i> @endif Roger Korsgaard</li>
            <li>@if(auth()->user()->profile_picture) <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" class="avatar" alt="Terry Torff"> @else <i class="bi bi-person-circle avatar"></i> @endif Terry Torff</li>
            <li>@if(auth()->user()->profile_picture) <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" class="avatar" alt="Angel Bergson"> @else <i class="bi bi-person-circle avatar"></i> @endif Angel Bergson</li>
            <li>@if(auth()->user()->profile_picture) <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" class="avatar" alt="Emerson Gouse"> @else <i class="bi bi-person-circle avatar"></i> @endif Emerson Gouse</li>
            <li>@if(auth()->user()->profile_picture) <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" class="avatar" alt="Corey Baptista"> @else <i class="bi bi-person-circle avatar"></i> @endif Corey Baptista</li>
            <li>@if(auth()->user()->profile_picture) <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" class="avatar" alt="Zain Culhane"> @else <i class="bi bi-person-circle avatar"></i> @endif Zain Culhane</li>
            <li>@if(auth()->user()->profile_picture) <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" class="avatar" alt="Randy Lipshutz"> @else <i class="bi bi-person-circle avatar"></i> @endif Randy Lipshutz</li>
            <li>@if(auth()->user()->profile_picture) <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" class="avatar" alt="Craig Botosh"> @else <i class="bi bi-person-circle avatar"></i> @endif Craig Botosh</li>
            <li><a href="#">View All</a></li>
        </ul>
    </div>
    <div class="events">
        <h4>Events</h4>
        <ul class="event-list">
            <li>10 Events Invites</li>
            <li>Design System Collaboration - Thu - Harpoon Mall, YK</li>
            <li>Web Dev 2.0 Meetup - Yoshkar-Ola, Russia</li>
            <li>Prada's Invitation Birthday</li>
        </ul>
    </div>
</aside>
@endif
