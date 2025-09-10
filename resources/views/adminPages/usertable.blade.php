<style>
    .main-panel {
        margin-left: 250px; /* Adjust based on sidebar width */
        max-width: calc(100% - 250px); /* Ensure content does not overflow */
    }
    .graph-container{
        display:flex;
        justify-content: center;
        align-content: center;
        margin:4rem;
    }
    .graph-container canvas{
        height:40px;
        width:60%;
    }
</style>
<div class="main-panel">
<div class="content-wrapper">
    <div class="row ">
      <div class="col-12 grid-margin">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Users Registered</h4>
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
                        <th>Users</th>
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
                                                <td>
                                                    @if ($user->profile_picture)
                                                        <img class="img-xs rounded-circle" src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile Picture">
                                                    @else
                                                        <i class="mdi mdi-account-circle img-xs rounded-circle" style="font-size:30px;"></i>
                                                    @endif
                                                    {{ $user->username }}

                                                </td>
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
                                                            <p><strong>User:</strong> {{ $user->username }}</p>
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
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> --}}
