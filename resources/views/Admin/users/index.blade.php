<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AfroConnect - User Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/mdi/css/materialdesignicons.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
    <style>
        body { font-family: 'Arial', sans-serif; background-color: #f4f4f4; }
        .main-panel { padding: 20px; }
        .content-wrapper { max-width: 1200px; margin: auto; }
        .card { border-radius: 8px; }
        .table-responsive { margin-top: 20px; }
        .badge { font-size: 0.9em; }
        .btn-sm { margin-right: 5px; }
        .sidebars {
            width: 250px;
            background: #191c24;
            color: white;
            padding: 20px;
            position: fixed;
            height: 100%;
        }
        .sidebars a {
            color: white;
            display: block;
            padding: 10px;
            margin-bottom: 10px;
            text-decoration: none;
            border-radius: 5px;
        }
       .sidebars a:hover { background: #272a32; }
        .sidebars a.active { background: #01164f; }
    </style>
</head>
<body>
    <div class="sidebars">
        <h4 class="text-white mb-4">AfroConnect Admin</h4>
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="mdi mdi-view-dashboard me-2"></i> Dashboard
        </a>
        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
            <i class="mdi mdi-account-multiple me-2"></i> Users
        </a>
        <a href="" class="{{ request()->routeIs('admin.posts.index') ? 'active' : '' }}">
            <i class="mdi mdi-post-outline me-2"></i> Posts
        </a>
        <a href="{{ route('admin.reports.index') }}" class="{{ request()->routeIs('admin.reports.index') ? 'active' : '' }}">
            <i class="mdi mdi-flag me-2"></i> Reports
        </a>
    </div>

    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Active Users</h4>
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Followers</th>
                                            <th>Reports</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $user)
                                            <tr>
                                                <td>{{ $user->username }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->followers_count }}</td>
                                                <td>{{ $user->reports_count }}</td>
                                                <td>
                                                    @if ($user->is_blocked)
                                                        <div class="badge badge-outline-danger">Blocked</div>
                                                    @elseif ($user->is_suspended)
                                                        <div class="badge badge-outline-warning">Suspended</div>
                                                    @elseif ($user->is_verified)
                                                        <div class="badge badge-outline-success">Verified</div>
                                                    @else
                                                        <div class="badge badge-outline-info">Active</div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}">
                                                        <i class="mdi mdi-eye"></i> View
                                                    </button>
                                                    @if ($user->is_blocked)
                                                        <form action="{{ route('admin.users.unblock', $user) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-sm btn-warning">
                                                                <i class="mdi mdi-lock-open"></i> Unblock
                                                            </button>
                                                        </form>
                                                    @elseif ($user->reports_count >= 3)
                                                        <form action="{{ route('admin.users.block', $user) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-sm btn-warning">
                                                                <i class="mdi mdi-lock"></i> Block
                                                            </button>
                                                        </form>
                                                    @endif
                                                    @if (!$user->is_verified && $user->followers_count >= 100)
                                                        <form action="{{ route('admin.users.verify', $user) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-sm btn-success">
                                                                <i class="mdi mdi-check-circle"></i> Verify
                                                            </button>
                                                        </form>
                                                    @endif
                                                    @if ($user->is_suspended)
                                                        <form action="{{ route('admin.users.unsuspend', $user) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-sm btn-info">
                                                                <i class="mdi mdi-play"></i> Unsuspend
                                                            </button>
                                                        </form>
                                                    @elseif ($user->reports_count >= 3)
                                                        <form action="{{ route('admin.users.suspend', $user) }}" method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-sm btn-info">
                                                                <i class="mdi mdi-pause"></i> Suspend
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">
                                                            <i class="mdi mdi-delete"></i> Delete
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <div class="modal fade" id="userModal{{ $user->id }}" tabindex="-1" aria-labelledby="userModalLabel{{ $user->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="userModalLabel{{ $user->id }}">User Details</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p><strong>Name:</strong> {{ $user->username }}</p>
                                                            <p><strong>Email:</strong> {{ $user->email }}</p>
                                                            <p><strong>Followers:</strong> {{ $user->followers_count }}</p>
                                                            <p><strong>Reports:</strong> {{ $user->reports_count }}</p>
                                                            <p><strong>Joined:</strong> {{ $user->created_at->format('Y-m-d H:i:s') }}</p>
                                                            <p><strong>Blocked:</strong> {{ $user->is_blocked ? 'Yes' : 'No' }}</p>
                                                            <p><strong>Verified:</strong> {{ $user->is_verified ? 'Yes' : 'No' }}</p>
                                                            <p><strong>Suspended:</strong> {{ $user->is_suspended ? 'Yes' : 'No' }}</p>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
