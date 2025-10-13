<style>
    .main-panel {
        margin-left: 250px;
        max-width: calc(100% - 250px);
    }
    .graph-container {
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 2rem 0;
        height: 300px;
        position: relative;
    }
    .graph-container canvas {
        width: 100% !important;
        height: 100% !important;
        max-width: 400px;
    }
    .chart-wrapper {
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
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
                  <h3 class="mb-0">{{ $totalUsers }}</h3>
                </div>
              </div>
              <div class="col-3">
                <div class="icon icon-box-success">
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
                  <h3 class="mb-0">{{ $totalPosts }}</h3>
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
                  <h3 class="mb-0">{{ $likesCount + $commentsCount + ($sharesCount ?? 0) }}</h3>
                  <p class="text-danger ms-2 mb-0 font-weight-medium">(Comments + Likes + Shares)</p>
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
                  <h3 class="mb-0">{{ $totalReports }}</h3>
                </div>
              </div>
              <div class="col-3">
                <div class="icon icon-box-success">
                  <span class="mdi mdi-arrow-top-right icon-item"></span>
                </div>
              </div>
            </div>
            <h6 class="text-muted font-weight-normal">Reports</h6>
          </div>
        </div>
      </div>
    </div>

    {{-- Charts Section --}}
    <div class="row">
      <div class="col-md-6 grid-margin stretch-card">
        <div class="card mb-4">
          <div class="card-body pt-0">
            <h5 class="card-title">Post Engagements</h5>
            <div class="graph-container">
              <div class="chart-wrapper">
                <canvas id="engagementChart"></canvas>
              </div>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card-body pt-0">
            <h5 class="card-title">Daily Reports</h5>
            <div class="graph-container">
              <div class="chart-wrapper">
                <canvas id="reportsChart"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Rest of your code (users table, etc.) remains the same --}}
    <div class="row">
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
                        <!-- Your action buttons here -->
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}">
                          <i class="mdi mdi-eye"></i> View
                        </button>
                        <!-- ... rest of your action buttons ... -->
                      </td>
                    </tr>
                    
                    <!-- User Modal -->
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

<script>
  // Pass PHP variables safely to JS
  const reportsChartData = @json($reportsChartData ?? []);
  const likesCount = @json($likesCount ?? 0);
  const commentsCount = @json($commentsCount ?? 0);
  const sharesCount = @json($sharesCount ?? 0);

  document.addEventListener('DOMContentLoaded', function () {
    console.log('Charts loading...');
    console.log('Likes:', likesCount, 'Comments:', commentsCount, 'Shares:', sharesCount);

    // --- ENGAGEMENT DOUGHNUT ---
    const engagementCanvas = document.getElementById('engagementChart');
    if (engagementCanvas) {
      console.log('Creating engagement chart...');
      
      // Check if we have any data to show
      const totalEngagement = likesCount + commentsCount + sharesCount;
      if (totalEngagement === 0) {
        // Show a message or placeholder if no data
        engagementCanvas.parentElement.innerHTML = `
          <div class="text-center text-muted">
            <i class="mdi mdi-chart-donut" style="font-size: 48px;"></i>
            <p>No engagement data available</p>
          </div>
        `;
      } else {
        new Chart(engagementCanvas.getContext('2d'), {
          type: 'doughnut',
          data: {
            labels: ['Likes', 'Comments', 'Shares'],
            datasets: [{
              data: [likesCount, commentsCount, sharesCount],
              backgroundColor: ['#1cc88a', '#36b9cc', '#f6c23e'],
              hoverBackgroundColor: ['#17a673', '#2c9faf', '#dda20a'],
              borderWidth: 2,
              borderColor: '#fff',
              cutout: '60%'
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
              legend: { 
                position: 'bottom', 
                labels: { 
                  boxWidth: 12, 
                  padding: 20,
                  usePointStyle: true
                } 
              },
              tooltip: {
                callbacks: {
                  label: function(context) {
                    const label = context.label || '';
                    const value = context.raw || 0;
                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                    const percentage = Math.round((value / total) * 100);
                    return `${label}: ${value} (${percentage}%)`;
                  }
                }
              }
            },
            animation: {
              animateScale: true,
              animateRotate: true
            }
          }
        });
        console.log('Engagement chart created successfully');
      }
    } else {
      console.error('Engagement canvas not found');
    }

    // --- REPORTS CHART ---
    const reportsCanvas = document.getElementById('reportsChart');
    if (reportsCanvas && reportsChartData.labels && reportsChartData.data) {
      console.log('Creating reports chart...');
      
      new Chart(reportsCanvas.getContext('2d'), {
        type: 'line',
        data: {
          labels: reportsChartData.labels,
          datasets: [{
            label: 'Daily Reports',
            data: reportsChartData.data,
            borderColor: '#e74a3b',
            backgroundColor: 'rgba(231, 74, 59, 0.1)',
            borderWidth: 3,
            pointRadius: 5,
            pointBackgroundColor: '#e74a3b',
            pointBorderColor: '#fff',
            pointHoverRadius: 7,
            pointBorderWidth: 2,
            fill: true,
            tension: 0.4
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: { 
            legend: { 
              display: false 
            } 
          },
          scales: {
            y: { 
              beginAtZero: true, 
              grid: { 
                display: true, 
                drawBorder: false 
              }, 
              ticks: { 
                maxTicksLimit: 6 
              } 
            },
            x: { 
              grid: { 
                display: false, 
                drawBorder: false 
              } 
            }
          }
        }
      });
      console.log('Reports chart created successfully');
    } else {
      console.warn('Reports chart data is missing or invalid');
    }
  });
</script>