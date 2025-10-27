<nav class="navbar p-0 fixed-top">
    <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
        <a class="navbar-brand brand-logo-mini" href="index.html"><img src="assets/images/logo-mini.svg" alt="logo" /></a>
    </div>
    <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
        </button>
        <ul class="navbar-nav w-100">
            <li class="nav-item w-100">
                <form class="nav-link mt-2 mt-md-0 d-none d-lg-flex search">
                    <input type="text" class="form-control" placeholder="Search users">
                </form>
            </li>
        </ul>
        <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item dropdown d-none d-lg-block">
                <a class="nav-link btn btn-success create-new-button" id="promoteButtonDropdown" data-bs-toggle="dropdown" aria-expanded="false" href="#">+ Promote to Admin</a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="promoteButtonDropdown">
                    <h6 class="p-3 mb-0">Select User to Promote</h6>
                    <div class="dropdown-divider"></div>
                    @foreach(\App\Models\User::where('user_type', 'user')->get() as $user)
                    <a class="dropdown-item preview-item promote-user" data-user-id="{{ $user->id }}">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-dark rounded-circle">
                                @if ($user->profile_picture)
                                <img class="img-xs rounded-circle" src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture">
                                @else
                                <i class="mdi mdi-account text-primary"></i>
                                @endif
                            </div>
                        </div>
                        <div class="preview-item-content">
                            <p class="preview-subject ellipsis mb-1">{{ $user->username }}</p>
                        </div>
                    </a>
                    @endforeach
                    <div class="dropdown-divider"></div>
                    <p class="p-3 mb-0 text-center">All non-admin users</p>
                </div>
            </li>
            <li class="nav-item dropdown d-none d-lg-block">
                <a class="nav-link btn btn-danger create-new-button" id="demoteButtonDropdown" data-bs-toggle="dropdown" aria-expanded="false" href="#">- Demote Admin</a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="demoteButtonDropdown">
                    <h6 class="p-3 mb-0">Select Admin to Demote</h6>
                    <div class="dropdown-divider"></div>
                    @foreach(\App\Models\User::where('user_type', 'admin')->where('id', '!=', auth()->id())->get() as $user)
                    <a class="dropdown-item preview-item demote-admin" data-user-id="{{ $user->id }}">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-dark rounded-circle">
                                @if ($user->profile_picture)
                                <img class="img-xs rounded-circle" src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture">
                                @else
                                <i class="mdi mdi-account text-danger"></i>
                                @endif
                            </div>
                        </div>
                        <div class="preview-item-content">
                            <p class="preview-subject ellipsis mb-1">{{ $user->username }}</p>
                        </div>
                    </a>
                    @endforeach
                    <div class="dropdown-divider"></div>
                    <p class="p-3 mb-0 text-center">All other admin users</p>
                </div>
            </li>
            <li class="nav-item nav-settings d-none d-lg-block">
                <a class="nav-link" href="#">
                    <i class="mdi mdi-view-grid"></i>
                </a>
            </li>
            <li class="nav-item dropdown border-left">
                <a class="nav-link count-indicator dropdown-toggle" id="messageDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="mdi mdi-email"></i>
                    <span class="count bg-success"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="messageDropdown">
                    <h6 class="p-3 mb-0">Messages</h6>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <img src="assets/images/faces/face4.jpg" alt="image" class="rounded-circle profile-pic">
                        </div>
                        <div class="preview-item-content">
                            <p class="preview-subject ellipsis mb-1">Mark send you a message</p>
                            <p class="text-muted mb-0"> 1 Minutes ago </p>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <img src="assets/images/faces/face2.jpg" alt="image" class="rounded-circle profile-pic">
                        </div>
                        <div class="preview-item-content">
                            <p class="preview-subject ellipsis mb-1">Cregh send you a message</p>
                            <p class="text-muted mb-0"> 15 Minutes ago </p>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <img src="assets/images/faces/face3.jpg" alt="image" class="rounded-circle profile-pic">
                        </div>
                        <div class="preview-item-content">
                            <p class="preview-subject ellipsis mb-1">Profile picture updated</p>
                            <p class="text-muted mb-0"> 18 Minutes ago </p>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <p class="p-3 mb-0 text-center">4 new messages</p>
                </div>
            </li>
            <li class="nav-item dropdown border-left">
                <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-bs-toggle="dropdown">
                    <i class="mdi mdi-bell"></i>
                    <span class="count bg-danger">{{ \App\Models\Report::where('status', 'pending')->count() + \App\Models\PostReport::where('status', 'pending')->count() }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
                    <h6 class="p-3 mb-0">Reports</h6>
                    <div class="dropdown-divider"></div>
                    @foreach(\App\Models\Report::where('status', 'pending')->latest()->take(5)->get() as $report)
                    <a class="dropdown-item preview-item" href="{{ route('admin.reports.index', $report->id) }}">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-dark rounded-circle">
                                <i class="mdi mdi-account-alert text-warning"></i>
                            </div>
                        </div>
                        <div class="preview-item-content">
                            <p class="text-muted ellipsis mb-0">Reason: {{ $report->reason }} - {{ $report->created_at->diffForHumans() }}</p>
                        </div>
                    </a>
                    @endforeach
                    @foreach(\App\Models\PostReport::where('status', 'pending')->latest()->take(5)->get() as $postReport)
                    <a class="dropdown-item preview-item" href="{{ route('admin.reports.index', $postReport->id) }}">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-dark rounded-circle">
                                <i class="mdi mdi-post text-info"></i>
                            </div>
                        </div>
                        <div class="preview-item-content">
                            <p class="preview-subject mb-1">Post Report: Post #{{ $postReport->post_id }}</p>
                            <p class="text-muted ellipsis mb-0">Reason: {{ $postReport->reason }} - {{ $postReport->created_at->diffForHumans() }}</p>
                        </div>
                    </a>
                    @endforeach
                    <div class="dropdown-divider"></div>
                    <p class="p-3 mb-0 text-center">See all reports</p>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link" id="profileDropdown" href="#" data-bs-toggle="dropdown">
                    <div class="navbar-profile">
                        @if (auth()->user() && auth()->user()->profile_picture)
                        <img class="img-xs rounded-circle" src="{{ asset(auth()->user()->profile_picture) }}" alt="Profile Picture">
                        @else
                        <i class="mdi mdi-account-circle img-xs rounded-circle"></i>
                        @endif
                        <p class="mb-0 d-none d-sm-block navbar-profile-name">{{ auth()->user()->username ?? 'Guest' }}</p>
                        <i class="mdi mdi-menu-down d-none d-sm-block"></i>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="profileDropdown">
                    <h6 class="p-3 mb-0">Profile</h6>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-dark rounded-circle">
                                <i class="mdi mdi-settings text-success"></i>
                            </div>
                        </div>
                        <div class="preview-item-content">
                            <p class="preview-subject mb-1">Settings</p>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('logout') }}" class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-dark rounded-circle">
                                <i class="mdi mdi-logout text-danger"></i>
                            </div>
                        </div>
                        <div class="preview-item-content">
                            <p class="preview-subject mb-1">Log out</p>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <p class="p-3 mb-0 text-center">Advanced settings</p>
                </div>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="mdi mdi-format-line-spacing"></span>
        </button>
    </div>
</nav>

<script>
    $(document).ready(function() {
        // CSRF Token for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Event handler for promoting a user
        $('.promote-user').on('click', function(e) {
            e.preventDefault();

            const userId = $(this).data('user-id');
            if (confirm('Are you sure you want to promote this user to an admin?')) {
                $.ajax({
                    url: '{{ route('admin.promoteUser') }}', // Updated route name
                    method: 'POST',
                    data: { user_id: userId },
                    success: function(response) {
                        alert(response.message);
                        location.reload(); // Reload the page to update the user lists
                    },
                    error: function(xhr) {
                        const errorMessage = xhr.responseJSON.message || 'An unexpected error occurred.';
                        alert(errorMessage);
                    }
                });
            }
        });

        // Event handler for demoting an admin
        $('.demote-admin').on('click', function(e) {
            e.preventDefault();

            const userId = $(this).data('user-id');
            if (confirm('Are you sure you want to demote this admin to a regular user?')) {
                $.ajax({
                    url: '{{ route('admin.demoteUser') }}', // Updated route name
                    method: 'POST',
                    data: { user_id: userId },
                    success: function(response) {
                        alert(response.message);
                        location.reload(); // Reload the page to update the user lists
                    },
                    error: function(xhr) {
                        const errorMessage = xhr.responseJSON.message || 'An unexpected error occurred.';
                        alert(errorMessage);
                    }
                });
            }
        });
    });
</script>
