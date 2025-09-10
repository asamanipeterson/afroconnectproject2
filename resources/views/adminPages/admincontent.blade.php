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
    <div class="row">
      <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-9">
                <div class="d-flex align-items-center align-self-start">
                  <h3 class="mb-0">{{$totalUsers}}</h3>
                  {{-- <p class="text-success ms-2 mb-0 font-weight-medium"></p> --}}
                </div>
              </div>
              <div class="col-3">
                <div class="icon icon-box-success ">
                  <span class="mdi mdi-account-multiple fs-4 text-primary"></span>
                </div>
              </div>
            </div>
            <h6 class="text-muted font-weight-normal">Active Users</h6>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-9">
                <div class="d-flex align-items-center align-self-start">
                  <h3 class="mb-0">{{$totalPosts}}</h3>
                  {{-- <p class="text-success ms-2 mb-0 font-weight-medium">+11%</p> --}}
                </div>
              </div>
              <div class="col-3">
                <div class="icon icon-box-primary">
                  <span class="mdi mdi-post-outline fs-4 text-success"></span>
                </div>
              </div>
            </div>
            <h6 class="text-muted font-weight-normal">Total Posts made</h6>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-9">
                <div class="d-flex align-items-center align-self-start">
                  <h3 class="mb-0">{{$likesCount+$commentsCount}}</h3>
                  <p class="text-danger ms-2 mb-0 font-weight-medium">(Comments + likes)</p>
                </div>
              </div>
              <div class="col-3">
                <div class="icon icon-box-danger">
                  <span class="mdi mdi-arrow-bottom-left icon-item"></span>
                </div>
              </div>
            </div>
            <h6 class="text-muted font-weight-normal">User Engagements on Posts</h6>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-9">
                <div class="d-flex align-items-center align-self-start">
                  <h3 class="mb-0">{{$totalReports}}</h3>
                  {{-- <p class="text-success ms-2 mb-0 font-weight-medium"></p> --}}
                </div>
              </div>
              <div class="col-3">
                <div class="icon icon-box-success ">
                  <span class="mdi mdi-arrow-top-right icon-item"></span>
                </div>
              </div>
            </div>
            <h6 class="text-muted font-weight-normal">Reports</h6>
          </div>
        </div>
      </div>
    </div>

{{-- {{ charts of the application }} --}}
    <div class="row">
    {{-- engagement graph --}}
      <div class="col-md-6  grid-margin stretch-card mb-4">
        <div class="card" style="margin-right:20px">
                <div class="card-body pt-0">
                    <div class="graph-container">
                    <canvas id="engagementChart"></canvas>
                    </div>
                </div>
        </div>


         <div class="card">
            <div class="card-body pt-0">
                    <div class="graph-container">
                    <canvas id="reportsChart"></canvas>
                    </div>
            </div>
         </div>
    </div>
    </div>


    {{-- <div class="row">
      <div class="col-sm-4 grid-margin">
        <div class="card">
          <div class="card-body">
            <h5>Revenue</h5>
            <div class="row">
              <div class="col-8 col-sm-12 col-xl-8 my-auto">
                <div class="d-flex d-sm-block d-md-flex align-items-center">
                  <h2 class="mb-0">$32123</h2>
                  <p class="text-success ms-2 mb-0 font-weight-medium">+3.5%</p>
                </div>
                <h6 class="text-muted font-weight-normal">11.38% Since last month</h6>
              </div>
              <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
                <i class="icon-lg mdi mdi-codepen text-primary ms-auto"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-4 grid-margin">
        <div class="card">
          <div class="card-body">
            <h5>Sales</h5>
            <div class="row">
              <div class="col-8 col-sm-12 col-xl-8 my-auto">
                <div class="d-flex d-sm-block d-md-flex align-items-center">
                  <h2 class="mb-0">$45850</h2>
                  <p class="text-success ms-2 mb-0 font-weight-medium">+8.3%</p>
                </div>
                <h6 class="text-muted font-weight-normal"> 9.61% Since last month</h6>
              </div>
              <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
                <i class="icon-lg mdi mdi-wallet-travel text-danger ms-auto"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-4 grid-margin">
        <div class="card">
          <div class="card-body">
            <h5>Purchase</h5>
            <div class="row">
              <div class="col-8 col-sm-12 col-xl-8 my-auto">
                <div class="d-flex d-sm-block d-md-flex align-items-center">
                  <h2 class="mb-0">$2039</h2>
                  <p class="text-danger ms-2 mb-0 font-weight-medium">-2.1% </p>
                </div>
                <h6 class="text-muted font-weight-normal">2.27% Since last month</h6>
              </div>
              <div class="col-4 col-sm-12 col-xl-4 text-center text-xl-right">
                <i class="icon-lg mdi mdi-monitor text-success ms-auto"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div> --}}

{{-- this is the part of the code  --}}
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



      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Visitors by Countries</h4>
            <div class="row">
              <div class="col-md-5">
                <div class="table-responsive">
                  <table class="table">
                    <tbody>
                      <tr>
                        <td>
                          <i class="flag-icon flag-icon-us"></i>
                        </td>
                        <td>USA</td>
                        <td class="text-right"> 1500 </td>
                        <td class="text-right font-weight-medium"> 56.35% </td>
                      </tr>
                      <tr>
                        <td>
                          <i class="flag-icon flag-icon-de"></i>
                        </td>
                        <td>Germany</td>
                        <td class="text-right"> 800 </td>
                        <td class="text-right font-weight-medium"> 33.25% </td>
                      </tr>
                      <tr>
                        <td>
                          <i class="flag-icon flag-icon-au"></i>
                        </td>
                        <td>Australia</td>
                        <td class="text-right"> 760 </td>
                        <td class="text-right font-weight-medium"> 15.45% </td>
                      </tr>
                      <tr>
                        <td>
                          <i class="flag-icon flag-icon-gb"></i>
                        </td>
                        <td>United Kingdom</td>
                        <td class="text-right"> 450 </td>
                        <td class="text-right font-weight-medium"> 25.00% </td>
                      </tr>
                      <tr>
                        <td>
                          <i class="flag-icon flag-icon-ro"></i>
                        </td>
                        <td>Romania</td>
                        <td class="text-right"> 620 </td>
                        <td class="text-right font-weight-medium"> 10.25% </td>
                      </tr>
                      <tr>
                        <td>
                          <i class="flag-icon flag-icon-br"></i>
                        </td>
                        <td>Brasil</td>
                        <td class="text-right"> 230 </td>
                        <td class="text-right font-weight-medium"> 75.00% </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="col-md-7">
                <div id="audience-map" class="vector-map"></div>
              </div>
            </div>
          </div>
        </div>
  </div>
</div>
 <script>
    // pass PHP variables safely to JS
    const reportsChartData = @json($reportsChartData ?? []);
    const engagementData = @json($engagementData ?? []);
    const likesCount = @json($likesCount ?? 0);
    const commentsCount = @json($commentsCount ?? 0);

    document.addEventListener('DOMContentLoaded', function () {

      // --- ENGAGEMENT DOUGHNUT (from DB counts) ---
      const engagementCanvas = document.getElementById('engagementChart');
      if (engagementCanvas) {
        new Chart(engagementCanvas.getContext('2d'), {
          type: 'doughnut',
          data: {
            labels: ['Likes', 'Comments'],
            datasets: [{
              data: [likesCount, commentsCount,],
              backgroundColor: ['#1cc88a', '#36b9cc'],
              hoverBackgroundColor: ['#17a673', '#2c9faf'],
              borderWidth: 0,
              cutout: '00%'
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
              legend: { position: 'bottom', labels: { boxWidth: 10, padding: 20 } }
            }
          }
        });
      }

      // --- REPORTS CHART (now dynamic) ---
      const reportsCanvas = document.getElementById('reportsChart');
      if (reportsCanvas && reportsChartData.labels && reportsChartData.data) {
        new Chart(reportsCanvas.getContext('2d'), {
          type: 'line',
          data: {
            labels: reportsChartData.labels,
            datasets: [{
              label: 'Daily Reports',
              data: reportsChartData.data,
              borderColor: '#e74a3b',
              backgroundColor: 'rgba(231, 74, 59, 0.05)',
              borderWidth: 2,
              pointRadius: 3,
              pointBackgroundColor: '#e74a3b',
              pointBorderColor: '#fff',
              pointHoverRadius: 5,
              fill: true,
              tension: 0.3
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
              y: { beginAtZero: true, grid: { display: true, drawBorder: false }, ticks: { maxTicksLimit: 5 } },
              x: { grid: { display: false, drawBorder: false } }
            }
          }
        });
      }
    });
</script>
