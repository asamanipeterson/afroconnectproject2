<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AfroConnect - Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/mdi/css/materialdesignicons.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
    <style>
        body { display: flex; min-height: 100vh; }
        .sidebar {
            width: 250px;
            background: #2c3e50;
            color: white;
            padding: 20px;
            position: fixed;
            height: 100%;
        }
        .sidebar a {
            color: white;
            display: block;
            padding: 10px;
            margin-bottom: 10px;
            text-decoration: none;
            border-radius: 5px;
        }
        .sidebar a:hover { background: #34495e; }
        .sidebar a.active { background: #3498db; }
        .main-panel {
            margin-left: 250px;
            padding: 20px;
            flex-grow: 1;
        }
        .content-wrapper { max-width: 1200px; margin: auto; }
        .card { border-radius: 8px; }
        .table-responsive { margin-top: 20px; }
        .badge { font-size: 0.9em; }
        .btn-sm { margin-right: 5px; }
        .details-column { max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
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

    <!-- Main Content -->
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Reports</h4>
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif
                            <!-- User Reports -->
                            <h5 class="mt-4">User Reports</h5>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Reported User</th>
                                            <th>Reporter</th>
                                            <th>Reason</th>
                                            <th>Details</th>
                                            <th>Status</th>
                                            <th>Reported At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($userReports as $report)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('admin.users.show', $report->reportedUser) }}">
                                                        {{ $report->reportedUser->username }}
                                                    </a>
                                                </td>
                                                <td>{{ $report->reporter->username }}</td>
                                                <td>{{ $report->reason ?? 'No reason provided' }}</td>
                                                <td class="details-column" title="{{ $report->details ?? '' }}">
                                                    {{ $report->details ?? 'No details provided' }}
                                                </td>
                                                <td>
                                                    <span class="badge {{ $report->status === 'pending' ? 'bg-warning' : 'bg-success' }}">
                                                        {{ ucfirst($report->status) }}
                                                    </span>
                                                </td>
                                                <td>{{ $report->created_at->format('Y-m-d H:i:s') }}</td>
                                                <td>
                                                    @if ($report->status === 'pending')
                                                        <form action="{{( $report) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-sm btn-success">Resolve</button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="7">No user reports found.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <!-- Post Reports -->
                            <h5 class="mt-4">Post Reports</h5>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Reported Post</th>
                                            <th>Posted By</th>
                                            <th>Reporter</th>
                                            <th>Reason</th>
                                            <th>Details</th>
                                            <th>Status</th>
                                            <th>Reported At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($postReports as $report)
                                            <tr>
                                                <td>{{ Str::limit($report->post->caption, 30, '...') }}</td>
                                                <td>
                                                    <a href="{{ route('admin.users.show', $report->post->user) }}">
                                                        {{ $report->post->user->username }}
                                                    </a>
                                                </td>
                                                <td>{{ $report->reporter->username }}</td>
                                                <td>{{ $report->reason ?? 'No reason provided' }}</td>
                                                <td class="details-column" title="{{ $report->details ?? '' }}">
                                                    {{ $report->details ?? 'No details provided' }}
                                                </td>
                                                <td>
                                                    <span class="badge {{ $report->status === 'pending' ? 'bg-warning' : 'bg-success' }}">
                                                        {{ ucfirst($report->status) }}
                                                    </span>
                                                </td>
                                                <td>{{ $report->created_at->format('Y-m-d H:i:s') }}</td>
                                                <td>
                                                    @if ($report->status === 'pending')
                                                        <form action="{{ route('reports.resolve.post', $report) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-sm btn-success">Resolve</button>
                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="8">No post reports found.</td></tr>
                                        @endforelse
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
