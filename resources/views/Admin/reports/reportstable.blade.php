<style>
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
<div class="main-panel" style="background: #fff;">
        <div class="content-wrapper" style="background: #fff;">
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
            <td>{{ Str::limit($report->post?->caption ?? 'Post deleted', 30, '...') }}</td>
            <td>
                @if ($report->post && $report->post->user)
                    <a href="{{ route('admin.users.show', $report->post->user) }}" class="re">
                        {{ $report->post->user->username }}
                    </a>
                @else
                    Post/user deleted
                @endif
            </td>
            <td>{{ $report->reporter->username ?? 'Unknown' }}</td>
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

