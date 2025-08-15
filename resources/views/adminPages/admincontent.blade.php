<<<<<<< HEAD
{{-- resources/views/Admin/admincontent.blade.php --}}
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
=======
<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-12 grid-margin stretch-card">
        <div class="card corona-gradient-card">
          <div class="card-body py-0 px-0 px-sm-3">
            <div class="row align-items-center">
              <div class="col-4 col-sm-3 col-xl-2">
                <img src="assets/images/dashboard/Group126@2x.png" class="gradient-corona-img img-fluid" alt="">
              </div>
              <div class="col-5 col-sm-7 col-xl-8 p-0">
                <h4 class="mb-1 mb-sm-0">Want even more features?</h4>
                <p class="mb-0 font-weight-normal d-none d-sm-block">Check out our Pro version with 5 unique layouts!</p>
              </div>
              <div class="col-3 col-sm-2 col-xl-2 ps-0 text-center">
                <span>
                  <a href="https://www.bootstrapdash.com/product/corona-admin-template/" target="_blank" class="btn btn-outline-light btn-rounded get-started-btn">Upgrade to PRO</a>
                </span>
              </div>
>>>>>>> c0d373d (admin side working left small touches)
            </div>
          </div>
        </div>
      </div>
<<<<<<< HEAD

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
=======
    </div>
    <div class="row">
      <a href="{{ route('admin.users.index') }}" class="col-xl-3 col-sm-6 grid-margin" style="text-decoration: none;" >
        <div class="stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-9">
                <div class="d-flex align-items-center align-self-start">
                  <h3 class="mb-0">{{$totalUsers}}</h3>
                  {{-- <p class="text-success ms-2 mb-0 font-weight-medium">+3.5%</p> --}}
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
      </a>
      <a href="{{ route('admin.reports.index') }}" class="col-xl-3 col-sm-6 grid-margin" style="text-decoration: none;">
        <div class="stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-9">
                <div class="d-flex align-items-center align-self-start">
                  <h3 class="mb-0">{{$totalPosts}}</h3>
                  <p class="text-success ms-2 mb-0 font-weight-medium">+11%</p>
                </div>
              </div>
              <div class="col-3">
                <div class="icon icon-box-success">
                  <span class="mdi mdi-post-outline fs-4 text-info"></span>
                </div>
              </div>
            </div>
            <h6 class="text-muted font-weight-normal">Total Post</h6>
          </div>
        </div>
      </div>
      </a>
      <a href="" class="col-xl-3 col-sm-6 grid-margin" style="text-decoration: none;">
        <div class="stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-9">
                <div class="d-flex align-items-center align-self-start">
                  <h3 class="mb-0">{{$likesCount + $commentsCount}}</h3>
                  <p class="text-danger ms-2 mb-0 font-weight-medium">(likes and comments)</p>
                </div>
              </div>
              <div class="col-3">
                <div class="icon icon-box-danger">
                  <span class="mdi mdi-chart-line fs-4 text-success"></span>
                </div>
              </div>
            </div>
            <h6 class="text-muted font-weight-normal">User Engagement</h6>
          </div>
        </div>
      </div>
      </a>
      <a href="{{ route('admin.reports.index') }}" class="col-xl-3 col-sm-6 grid-margin" style="text-decoration: none;">
        <div class="stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-9">
                <div class="d-flex align-items-center align-self-start">
                  <h3 class="mb-0">$31.53</h3>
                  <p class="text-success ms-2 mb-0 font-weight-medium">+3.5%</p>
                </div>
              </div>
              <div class="col-3">
                <div class="icon icon-box-success">
                  <span class="mdi mdi-flag fs-4 text-danger"></span>
                </div>
              </div>
            </div>
            <h6 class="text-muted font-weight-normal">Reports</h6>
          </div>
        </div>
      </div>
      </a>
    </div>
    <div class="row">
      <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Transaction History</h4>
            <canvas id="transaction-history" class="transaction-chart"></canvas>
            <div class="bg-gray-dark d-flex d-md-block d-xl-flex flex-row py-3 px-4 px-md-3 px-xl-4 rounded mt-3">
              <div class="text-md-center text-xl-left">
                <h6 class="mb-1">Transfer to Paypal</h6>
                <p class="text-muted mb-0">07 Jan 2019, 09:12AM</p>
              </div>
              <div class="align-self-center flex-grow text-right text-md-center text-xl-right py-md-2 py-xl-0">
                <h6 class="font-weight-bold mb-0">$236</h6>
              </div>
            </div>
            <div class="bg-gray-dark d-flex d-md-block d-xl-flex flex-row py-3 px-4 px-md-3 px-xl-4 rounded mt-3">
              <div class="text-md-center text-xl-left">
                <h6 class="mb-1">Transfer to Stripe</h6>
                <p class="text-muted mb-0">07 Jan 2019, 09:12AM</p>
              </div>
              <div class="align-self-center flex-grow text-right text-md-center text-xl-right py-md-2 py-xl-0">
                <h6 class="font-weight-bold mb-0">$593</h6>
              </div>
            </div>
          </div>
        </div>
      </div>
      {{-- <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="d-flex flex-row justify-content-between">
              <h4 class="card-title mb-1">Open Projects</h4>
              <p class="text-muted mb-1">Your data status</p>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="preview-list">
                  <div class="preview-item border-bottom">
                    <div class="preview-thumbnail">
                      <div class="preview-icon bg-primary">
                        <i class="mdi mdi-file-document"></i>
                      </div>
                    </div>
                    <div class="preview-item-content d-sm-flex flex-grow">
                      <div class="flex-grow">
                        <h6 class="preview-subject">Admin dashboard design</h6>
                        <p class="text-muted mb-0">Broadcast web app mockup</p>
                      </div>
                      <div class="me-auto text-sm-right pt-2 pt-sm-0">
                        <p class="text-muted">15 minutes ago</p>
                        <p class="text-muted mb-0">30 tasks, 5 issues</p>
                      </div>
                    </div>
                  </div>
                  <div class="preview-item border-bottom">
                    <div class="preview-thumbnail">
                      <div class="preview-icon bg-success">
                        <i class="mdi mdi-cloud-download"></i>
                      </div>
                    </div>
                    <div class="preview-item-content d-sm-flex flex-grow">
                      <div class="flex-grow">
                        <h6 class="preview-subject">Wordpress Development</h6>
                        <p class="text-muted mb-0">Upload new design</p>
                      </div>
                      <div class="me-auto text-sm-right pt-2 pt-sm-0">
                        <p class="text-muted">1 hour ago</p>
                        <p class="text-muted mb-0">23 tasks, 5 issues</p>
                      </div>
                    </div>
                  </div>
                  <div class="preview-item border-bottom">
                    <div class="preview-thumbnail">
                      <div class="preview-icon bg-info">
                        <i class="mdi mdi-clock"></i>
                      </div>
                    </div>
                    <div class="preview-item-content d-sm-flex flex-grow">
                      <div class="flex-grow">
                        <h6 class="preview-subject">Project meeting</h6>
                        <p class="text-muted mb-0">New project discussion</p>
                      </div>
                      <div class="me-auto text-sm-right pt-2 pt-sm-0">
                        <p class="text-muted">35 minutes ago</p>
                        <p class="text-muted mb-0">15 tasks, 2 issues</p>
                      </div>
                    </div>
                  </div>
                  <div class="preview-item border-bottom">
                    <div class="preview-thumbnail">
                      <div class="preview-icon bg-danger">
                        <i class="mdi mdi-email-open"></i>
                      </div>
                    </div>
                    <div class="preview-item-content d-sm-flex flex-grow">
                      <div class="flex-grow">
                        <h6 class="preview-subject">Broadcast Mail</h6>
                        <p class="text-muted mb-0">Sent release details to team</p>
                      </div>
                      <div class="me-auto text-sm-right pt-2 pt-sm-0">
                        <p class="text-muted">55 minutes ago</p>
                        <p class="text-muted mb-0">35 tasks, 7 issues</p>
                      </div>
                    </div>
                  </div>
                  <div class="preview-item">
                    <div class="preview-thumbnail">
                      <div class="preview-icon bg-warning">
                        <i class="mdi mdi-chart-pie"></i>
                      </div>
                    </div>
                    <div class="preview-item-content d-sm-flex flex-grow">
                      <div class="flex-grow">
                        <h6 class="preview-subject">UI Design</h6>
                        <p class="text-muted mb-0">New application planning</p>
                      </div>
                      <div class="me-auto text-sm-right pt-2 pt-sm-0">
                        <p class="text-muted">50 minutes ago</p>
                        <p class="text-muted mb-0">27 tasks, 4 issues</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div> --}}
    </div>

    {{-- This where users listwill be  --}}
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
                <h6 class="text-muted font-weight-normal">9.61% Since last month</h6>
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
                  <p class="text-danger ms-2 mb-0 font-weight-medium">-2.1%</p>
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


    {{-- <div class="row">
      <div class="col-12 grid-margin">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Order Status</h4>
            <div class="table-responsive">
              <table class="table">
                <thead>
                  <tr>
                    <th>
                      <div class="form-check form-check-muted m-0">
                        <label class="form-check-label">
                          <input type="checkbox" class="form-check-input">
                        </label>
                      </div>
                    </th>
                    <th>Client Name</th>
                    <th>Order No</th>
                    <th>Product Cost</th>
                    <th>Project</th>
                    <th>Payment Mode</th>
                    <th>Start Date</th>
                    <th>Payment Status</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <div class="form-check form-check-muted m-0">
                        <label class="form-check-label">
                          <input type="checkbox" class="form-check-input">
                        </label>
                      </div>
                    </td>
                    <td>
                      <img src="assets/images/faces/face1.jpg" alt="image" />
                      <span class="ps-2">Henry Klein</span>
                    </td>
                    <td>02312</td>
                    <td>$14,500</td>
                    <td>Dashboard</td>
                    <td>Credit card</td>
                    <td>04 Dec 2019</td>
                    <td>
                      <div class="badge badge-outline-success">Approved</div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="form-check form-check-muted m-0">
                        <label class="form-check-label">
                          <input type="checkbox" class="form-check-input">
                        </label>
                      </div>
                    </td>
                    <td>
                      <img src="assets/images/faces/face2.jpg" alt="image" />
                      <span class="ps-2">Estella Bryan</span>
                    </td>
                    <td>02312</td>
                    <td>$14,500</td>
                    <td>Website</td>
                    <td>Cash on delivered</td>
                    <td>04 Dec 2019</td>
                    <td>
                      <div class="badge badge-outline-warning">Pending</div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="form-check form-check-muted m-0">
                        <label class="form-check-label">
                          <input type="checkbox" class="form-check-input">
                        </label>
                      </div>
                    </td>
                    <td>
                      <img src="assets/images/faces/face5.jpg" alt="image" />
                      <span class="ps-2">Lucy Abbott</span>
                    </td>
                    <td>02312</td>
                    <td>$14,500</td>
                    <td>App design</td>
                    <td>Credit card</td>
                    <td>04 Dec 2019</td>
                    <td>
                      <div class="badge badge-outline-danger">Rejected</div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="form-check form-check-muted m-0">
                        <label class="form-check-label">
                          <input type="checkbox" class="form-check-input">
                        </label>
                      </div>
                    </td>
                    <td>
                      <img src="assets/images/faces/face3.jpg" alt="image" />
                      <span class="ps-2">Peter Gill</span>
                    </td>
                    <td>02312</td>
                    <td>$14,500</td>
                    <td>Development</td>
                    <td>Online Payment</td>
                    <td>04 Dec 2019</td>
                    <td>
                      <div class="badge badge-outline-success">Approved</div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="form-check form-check-muted m-0">
                        <label class="form-check-label">
                          <input type="checkbox" class="form-check-input">
                        </label>
                      </div>
                    </td>
                    <td>
                      <img src="assets/images/faces/face4.jpg" alt="image" />
                      <span class="ps-2">Sallie Reyes</span>
                    </td>
                    <td>02312</td>
                    <td>$14,500</td>
                    <td>Website</td>
                    <td>Credit card</td>
                    <td>04 Dec 2019</td>
                    <td>
                      <div class="badge badge-outline-success">Approved</div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div> --}}

{{-- Reports --}}
    {{-- <div class="row">
      <div class="col-md-6 col-xl-4 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="d-flex flex-row justify-content-between">
              <h4 class="card-title">Messages</h4>
              <p class="text-muted mb-1 small">View all</p>
            </div>
            <div class="preview-list">
              <div class="preview-item border-bottom">
                <div class="preview-thumbnail">
                  <img src="assets/images/faces/face6.jpg" alt="image" class="rounded-circle" />
                </div>
                <div class="preview-item-content d-flex flex-grow">
                  <div class="flex-grow">
                    <div class="d-flex d-md-block d-xl-flex justify-content-between">
                      <h6 class="preview-subject">Leonard</h6>
                      <p class="text-muted text-small">5 minutes ago</p>
                    </div>
                    <p class="text-muted">Well, it seems to be working now.</p>
                  </div>
                </div>
              </div>
              <div class="preview-item border-bottom">
                <div class="preview-thumbnail">
                  <img src="assets/images/faces/face8.jpg" alt="image" class="rounded-circle" />
                </div>
                <div class="preview-item-content d-flex flex-grow">
                  <div class="flex-grow">
                    <div class="d-flex d-md-block d-xl-flex justify-content-between">
                      <h6 class="preview-subject">Luella Mills</h6>
                      <p class="text-muted text-small">10 Minutes Ago</p>
                    </div>
                    <p class="text-muted">Well, it seems to be working now.</p>
                  </div>
                </div>
              </div>
              <div class="preview-item border-bottom">
                <div class="preview-thumbnail">
                  <img src="assets/images/faces/face9.jpg" alt="image" class="rounded-circle" />
                </div>
                <div class="preview-item-content d-flex flex-grow">
                  <div class="flex-grow">
                    <div class="d-flex d-md-block d-xl-flex justify-content-between">
                      <h6 class="preview-subject">Ethel Kelly</h6>
                      <p class="text-muted text-small">2 Hours Ago</p>
                    </div>
                    <p class="text-muted">Please review the tickets</p>
                  </div>
                </div>
              </div>
              <div class="preview-item border-bottom">
                <div class="preview-thumbnail">
                  <img src="assets/images/faces/face11.jpg" alt="image" class="rounded-circle" />
                </div>
                <div class="preview-item-content d-flex flex-grow">
                  <div class="flex-grow">
                    <div class="d-flex d-md-block d-xl-flex justify-content-between">
                      <h6 class="preview-subject">Herman May</h6>
                      <p class="text-muted text-small">4 Hours Ago</p>
                    </div>
                    <p class="text-muted">Thanks a lot. It was easy to fix it.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6 col-xl-4 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Portfolio Slide</h4>
            <div class="owl-carousel owl-theme full-width owl-carousel-dash portfolio-carousel" id="owl-carousel-basic">
              <div class="item">
                <img src="assets/images/dashboard/Rectangle.jpg" alt="">
              </div>
              <div class="item">
                <img src="assets/images/dashboard/Img_5.jpg" alt="">
              </div>
              <div class="item">
                <img src="assets/images/dashboard/img_6.jpg" alt="">
              </div>
            </div>
            <div class="d-flex py-4">
              <div class="preview-list w-100">
                <div class="preview-item p-0">
                  <div class="preview-thumbnail">
                    <img src="assets/images/faces/face12.jpg" class="rounded-circle" alt="">
                  </div>
                  <div class="preview-item-content d-flex flex-grow">
                    <div class="flex-grow">
                      <div class="d-flex d-md-block d-xl-flex justify-content-between">
                        <h6 class="preview-subject">CeeCee Bass</h6>
                        <p class="text-muted text-small">4 Hours Ago</p>
                      </div>
                      <p class="text-muted">Well, it seems to be working now.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <p class="text-muted">Well, it seems to be working now.</p>
            <div class="progress progress-md portfolio-progress">
              <div class="progress-bar bg-success" role="progressbar" style="width: 50%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12 col-xl-4 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">To do list</h4>
            <div class="add-items d-flex">
              <input type="text" class="form-control todo-list-input" placeholder="enter task..">
              <button class="add btn btn-primary todo-list-add-btn">Add</button>
            </div>
            <div class="list-wrapper">
              <ul class="d-flex flex-column-reverse text-white todo-list todo-list-custom">
                <li>
                  <div class="form-check form-check-primary">
                    <label class="form-check-label">
                      <input class="checkbox" type="checkbox">Create invoice</label>
                  </div>
                  <i class="remove mdi mdi-close-box"></i>
                </li>
                <li>
                  <div class="form-check form-check-primary">
                    <label class="form-check-label">
                      <input class="checkbox" type="checkbox">Meeting with Alita</label>
                  </div>
                  <i class="remove mdi mdi-close-box"></i>
                </li>
                <li class="completed">
                  <div class="form-check form-check-primary">
                    <label class="form-check-label">
                      <input class="checkbox" type="checkbox" checked>Prepare for presentation</label>
                  </div>
                  <i class="remove mdi mdi-close-box"></i>
                </li>
                <li>
                  <div class="form-check form-check-primary">
                    <label class="form-check-label">
                      <input class="checkbox" type="checkbox">Plan weekend outing</label>
                  </div>
                  <i class="remove mdi mdi-close-box"></i>
                </li>
                <li>
                  <div class="form-check form-check-primary">
                    <label class="form-check-label">
                      <input class="checkbox" type="checkbox">Pick up kids from school</label>
                  </div>
                  <i class="remove mdi mdi-close-box"></i>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div> --}}
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title">Visitors by Countries</h4>
            <div class="row">
              {{-- <div class="col-md-5">
                <div class="table-responsive">
                  <table class="table">
                    <tbody>
                      <tr>
                        <td>
                          <i class="flag-icon flag-icon-us"></i>
                        </td>
                        <td>USA</td>
                        <td class="text-right">1500</td>
                        <td class="text-right font-weight-medium">56.35%</td>
                      </tr>
                      <tr>
                        <td>
                          <i class="flag-icon flag-icon-de"></i>
                        </td>
                        <td>Germany</td>
                        <td class="text-right">800</td>
                        <td class="text-right font-weight-medium">33.25%</td>
                      </tr>
                      <tr>
                        <td>
                          <i class="flag-icon flag-icon-au"></i>
                        </td>
                        <td>Australia</td>
                        <td class="text-right">760</td>
                        <td class="text-right font-weight-medium">15.45%</td>
                      </tr>
                      <tr>
                        <td>
                          <i class="flag-icon flag-icon-gb"></i>
                        </td>
                        <td>United Kingdom</td>
                        <td class="text-right">450</td>
                        <td class="text-right font-weight-medium">25.00%</td>
                      </tr>
                      <tr>
                        <td>
                          <i class="flag-icon flag-icon-ro"></i>
                        </td>
                        <td>Romania</td>
                        <td class="text-right">620</td>
                        <td class="text-right font-weight-medium">10.25%</td>
                      </tr>
                      <tr>
                        <td>
                          <i class="flag-icon flag-icon-br"></i>
                        </td>
                        <td>Brasil</td>
                        <td class="text-right">230</td>
                        <td class="text-right font-weight-medium">75.00%</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div> --}}
              <div class="col-md-7 map-div">
                <div id="audience-map" class="vector-map"></div>
              </div>
>>>>>>> c0d373d (admin side working left small touches)
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<<<<<<< HEAD

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
<!-- partial:partials/_footer.html -->
<footer class="footer">
  <div class="d-sm-flex justify-content-center justify-content-sm-between">
    <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © bootstrapdash.com 2021</span>
    <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center"> Free <a href="https://www.bootstrapdash.com/bootstrap-admin-template/" target="_blank">Bootstrap admin template</a> from Bootstrapdash.com</span>
  </div>
</footer>
<!-- partial -->
=======
>>>>>>> c0d373d (admin side working left small touches)
</div>
