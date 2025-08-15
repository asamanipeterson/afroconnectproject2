<div class="main-panel">
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vertical Dashboard with Graphs</title>
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Material Design Icons -->
  <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.2.96/css/materialdesignicons.min.css" rel="stylesheet">
      {{-- <link rel="stylesheet" href="assets/css/style.css"> --}}
  <style>
    .dashboard-card { transition: all 0.3s ease; border-radius: 12px; overflow: hidden; margin-bottom: 24px; }
    .dashboard-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
    .metric-icon { width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; border-radius: 10px; }
    .metric-value { font-size: 1.75rem; font-weight: 700; }
    .metric-title { font-size: 0.8rem; letter-spacing: 0.5px; color: #6c757d; text-transform: uppercase; font-weight: 600; }
    .metric-change.positive { color: #28a745; }
    .metric-change.negative { color: #dc3545; }
    .graph-container { height: 200px; margin-top: 16px; }
    .card-header { border-bottom: none; background-color: transparent; padding-bottom: 0; }
    /* Ensure canvas area fits */
    .graph-container canvas { width: 100% !important; height: 200px !important; }
  </style>
</head>
<body>
    {{-- @include('adminPages.navbar') --}}
    {{-- @include('adminPages.sidebar') --}}
  <div class="container py-4">
    <div class="row">
      <div class="col-lg-6">
        <!-- Users Card with Graph -->
        <div class="card dashboard-card border-0 shadow-sm">
          <div class="card-header">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <h6 class="metric-title mb-2">Total Users</h6>
                <h2 class="metric-value text-dark mb-0">{{ $totalUsers }}</h2>
              </div>
              <div class="metric-icon bg-primary bg-opacity-10">
                <i class="mdi mdi-account-multiple fs-4 text-primary"></i>
              </div>
            </div>
            <div class="d-flex align-items-center mt-2">
              <span class="metric-change positive">
                <i class="mdi mdi-arrow-top-right me-1"></i>+5.2%
              </span>
              <span class="text-muted small ms-2">vs last week</span>
            </div>
          </div>
          <div class="card-body pt-0">
            <div class="graph-container">
              <canvas id="usersChart"></canvas>
            </div>
          </div>
        </div>

        <!-- Posts Card with Graph -->
        <div class="card dashboard-card border-0 shadow-sm">
          <div class="card-header">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <h6 class="metric-title mb-2">Total Posts</h6>
                <h2 class="metric-value text-dark mb-0">{{ $totalPosts }}</h2>
              </div>
              <div class="metric-icon bg-info bg-opacity-10">
                <i class="mdi mdi-post-outline fs-4 text-info"></i>
              </div>
            </div>
          </div>
          <div class="card-body pt-0">
            <div class="graph-container">
              <canvas id="postsChart"></canvas>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <!-- Reports Card with Graph -->
        <div class="card dashboard-card border-0 shadow-sm">
          <div class="card-header">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <h6 class="metric-title mb-2">Reports </h6>
                <h2 class="metric-value text-dark mb-0">78</h2>
              </div>
              <div class="metric-icon bg-danger bg-opacity-10">
                <i class="mdi mdi-flag fs-4 text-danger"></i>
              </div>
            </div>
            <div class="d-flex align-items-center mt-2">
              <span class="metric-change negative">
                <i class="mdi mdi-arrow-top-right me-1"></i>+1.4%
              </span>
              <span class="text-muted small ms-2">vs last week</span>
            </div>
          </div>
          <div class="card-body pt-0">
            <div class="graph-container">
              <canvas id="reportsChart"></canvas>
            </div>
          </div>
        </div>

        <!-- Engagement Card with Graph -->
        <div class="card dashboard-card border-0 shadow-sm">
          <div class="card-header">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <h6 class="metric-title mb-2">User Engagement</h6>
                <h2 class="metric-value text-dark mb-0">
                  @if(isset($engagementPerDay) && is_array($engagementPerDay) && count($engagementPerDay))
                    {{ $engagementPerDay[count($engagementPerDay)-1]['rate'] ?? 0 }}%
                  @else
                    {{-- fallback: (likes+comments+shares)/totalUsers *100 --}}
                    @php
                      $likes = $likesCount ?? 0;
                      $comments = $commentsCount ?? 0;
                      $shares = $sharesCount ?? 0;
                      $tusers = $totalUsers ?? 0;
                      $fallbackRate = $tusers > 0 ? round((($likes + $comments + $shares) / $tusers) * 100, 2) : 0;
                    @endphp
                    {{ $fallbackRate }}%
                  @endif
                </h2>
              </div>
              <div class="metric-icon bg-success bg-opacity-10">
                <i class="mdi mdi-chart-line fs-4 text-success"></i>
              </div>
            </div>
            <div class="d-flex align-items-center mt-2">
              <span class="metric-change positive">
                <i class="mdi mdi-arrow-top-right me-1"></i>
                @if(isset($engagementPerDay) && is_array($engagementPerDay) && count($engagementPerDay) >= 2)
                  {{ number_format(($engagementPerDay[count($engagementPerDay)-1]['rate'] - $engagementPerDay[count($engagementPerDay)-2]['rate']), 2) }}%
                @else
                  0.00%
                @endif
              </span>
              <span class="text-muted small ms-2">vs last week</span>
            </div>
          </div>
          <div class="card-body pt-0">
            <div class="graph-container">
              <canvas id="engagementChart"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap 5 JS Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Chart.js (single include) -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <script>
    // pass PHP variables safely to JS
    const usersPerDay = @json($usersPerDay ?? []);
    const postsPerDay = @json($postsPerDay ?? []);
    const likesCount = @json($likesCount ?? 0);
    const commentsCount = @json($commentsCount ?? 0);
    const sharesCount = @json($sharesCount ?? 0);
    const engagementPerDay = @json($engagementPerDay ?? []);
    const totalUsers = @json($totalUsers ?? 0);

    document.addEventListener('DOMContentLoaded', function () {
      // --- POSTS CHART ---
      const postsCanvas = document.getElementById('postsChart');
      if (postsCanvas && postsPerDay.length) {
        const postsLabels = postsPerDay.map(i => i.date);
        const postsCounts = postsPerDay.map(i => i.count);

        new Chart(postsCanvas.getContext('2d'), {
          type: 'line',
          data: {
            labels: postsLabels,
            datasets: [{
              label: 'Posts Per Day',
              data: postsCounts,
              fill: true,
              backgroundColor: 'rgba(0, 123, 255, 0.2)',
              borderColor: 'rgba(0, 123, 255, 1)',
              borderWidth: 2,
              tension: 0.4
            }]
          },
          options: {
            responsive: true,
            scales: {
              y: { beginAtZero: true, ticks: { precision: 0 } }
            },
            plugins: { legend: { display: false } }
          }
        });
      }

      // --- USERS CHART ---
      const usersCanvas = document.getElementById('usersChart');
      if (usersCanvas && usersPerDay.length) {
        const userLabels = usersPerDay.map(item => {
          const d = new Date(item.date);
          return `${d.getDate()}/${d.getMonth() + 1}`;
        });
        const userCounts = usersPerDay.map(item => item.count);

        new Chart(usersCanvas.getContext('2d'), {
          type: 'line',
          data: {
            labels: userLabels,
            datasets: [{
              label: 'Users Per Day',
              data: userCounts,
              borderColor: '#0d6efd',
              backgroundColor: 'rgba(13, 110, 253, 0.1)',
              tension: 0.4,
              fill: true,
              pointRadius: 4,
              pointHoverRadius: 6
            }]
          },
          options: {
            responsive: true,
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
            plugins: { legend: { display: false } }
          }
        });
      }

      // --- REPORTS CHART (kept static) ---
      const reportsCanvas = document.getElementById('reportsChart');
      if (reportsCanvas) {
        new Chart(reportsCanvas.getContext('2d'), {
          type: 'line',
          data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
              label: 'Reports',
              data: [12, 19, 15, 20, 14, 25, 18],
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

      // --- ENGAGEMENT DOUGHNUT (from DB counts) ---
      const engagementCanvas = document.getElementById('engagementChart');
      if (engagementCanvas) {
        new Chart(engagementCanvas.getContext('2d'), {
          type: 'doughnut',
          data: {
            labels: ['Likes', 'Comments', 'Shares'],
            datasets: [{
              data: [likesCount, commentsCount, sharesCount],
              backgroundColor: ['#1cc88a', '#36b9cc', '#f6c23e'],
              hoverBackgroundColor: ['#17a673', '#2c9faf', '#dda20a'],
              borderWidth: 0,
              cutout: '80%'
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
    });
  </script>
</body>
</html>
<!-- content-wrapper ends -->

<!-- partial -->
</div>
