
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
    {{-- <div class="row">
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
            </div>
          </div>
        </div>
      </div>
    </div> --}}


    <div class="row">
      <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-9">
                <div class="d-flex align-items-center align-self-start">
                  <h3 class="mb-0">{{$totalUsers}}</h3>
                  <p class="text-success ms-2 mb-0 font-weight-medium">+3.5%</p>
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
                  <p class="text-success ms-2 mb-0 font-weight-medium">+11%</p>
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
                  <h3 class="mb-0">{{$commentsCount+$likesCount}}</h3>
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
                  <h3 class="mb-0">$31.53</h3>
                  <p class="text-success ms-2 mb-0 font-weight-medium">+3.5%</p>
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
                <h6 class="mb-1">Tranfer to Stripe</h6>
                <p class="text-muted mb-0">07 Jan 2019, 09:12AM</p>
              </div>
              <div class="align-self-center flex-grow text-right text-md-center text-xl-right py-md-2 py-xl-0">
                <h6 class="font-weight-bold mb-0">$593</h6>
              </div>
            </div>
          </div>
        </div>
      </div>

    {{-- engagement graph --}}
      <div class="col-md-8 grid-margin stretch-card">
        <div class="card">
        <div class="card-body pt-0">
            <div class="graph-container">
              <canvas id="engagementChart"></canvas>
            </div>
        </div>


        {{-- <div class="card-body pt-0">
            <div class="graph-container">
              <canvas id="reportsChart"></canvas>
            </div>
          </div>
        </div> --}}

        {{-- <div class="card-body pt-0">
            <div class="graph-container">
              <canvas id="usersChart"></canvas>
            </div>
          </div> --}}

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
                        <th>User</th>
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
    <div class="row">
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
                    <p class="text-muted">Thanks a lot. It was easy to fix it .</p>
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
            <p class="text-muted">Well, it seems to be working now. </p>
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
                      <input class="checkbox" type="checkbox"> Create invoice </label>
                  </div>
                  <i class="remove mdi mdi-close-box"></i>
                </li>
                <li>
                  <div class="form-check form-check-primary">
                    <label class="form-check-label">
                      <input class="checkbox" type="checkbox"> Meeting with Alita </label>
                  </div>
                  <i class="remove mdi mdi-close-box"></i>
                </li>
                <li class="completed">
                  <div class="form-check form-check-primary">
                    <label class="form-check-label">
                      <input class="checkbox" type="checkbox" checked> Prepare for presentation </label>
                  </div>
                  <i class="remove mdi mdi-close-box"></i>
                </li>
                <li>
                  <div class="form-check form-check-primary">
                    <label class="form-check-label">
                      <input class="checkbox" type="checkbox"> Plan weekend outing </label>
                  </div>
                  <i class="remove mdi mdi-close-box"></i>
                </li>
                <li>
                  <div class="form-check form-check-primary">
                    <label class="form-check-label">
                      <input class="checkbox" type="checkbox"> Pick up kids from school </label>
                  </div>
                  <i class="remove mdi mdi-close-box"></i>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
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
  </div>
</div>
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


      // --- USERS CHART ---
    const usersCanvas = document.getElementById('usersChart');

    if (usersCanvas && usersPerDay.length) {
      const userLabels = usersPerDay.map(item => {
        const d = new Date(item.date);
        return `${d.getDate()}/${d.getMonth() + 1}`;
      });
      const userCounts = usersPerDay.map(item => item.count);

      new Chart(usersCanvas.getContext('2d'), {
        type: 'doughnut',
        data: {
          labels: userLabels,
          datasets: [{
            label: 'Users Registered Per Day',
            data: userCounts,
            backgroundColor: [
              'rgba(13, 110, 253, 0.6)',
              'rgba(40, 167, 69, 0.6)',
              'rgba(255, 99, 132, 0.6)',
              'rgba(255, 193, 7, 0.6)',
              'rgba(111, 66, 193, 0.6)'
            ],
            borderColor: [
              'rgba(13, 110, 253, 1)',
              'rgba(40, 167, 69, 1)',
              'rgba(255, 99, 132, 1)',
              'rgba(255, 193, 7, 1)',
              'rgba(111, 66, 193, 1)'
            ],
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              display: true,
              position: 'top'
            },
            title: {
              display: true,
              text: 'Users Registered Per Day'
            }
          }
        }
      });
    } else {
      console.error('Canvas element not found or no data available');
    }

      // --- REPORTS CHART (kept static) ---
    //   const reportsCanvas = document.getElementById('reportsChart');
    //   if (reportsCanvas) {
    //     new Chart(reportsCanvas.getContext('2d'), {
    //       type: 'line',
    //       data: {
    //         labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
    //         datasets: [{
    //           label: 'Reports',
    //           data: [12, 19, 15, 20, 14, 25, 18],
    //           borderColor: '#e74a3b',
    //           backgroundColor: 'rgba(231, 74, 59, 0.05)',
    //           borderWidth: 2,
    //           pointRadius: 3,
    //           pointBackgroundColor: '#e74a3b',
    //           pointBorderColor: '#fff',
    //           pointHoverRadius: 5,
    //           fill: true,
    //           tension: 0.3
    //         }]
    //       },
    //       options: {
    //         responsive: true,
    //         maintainAspectRatio: false,
    //         plugins: { legend: { display: false } },
    //         scales: {
    //           y: { beginAtZero: true, grid: { display: true, drawBorder: false }, ticks: { maxTicksLimit: 5 } },
    //           x: { grid: { display: false, drawBorder: false } }
    //         }
    //       }
    //     });
    //   }

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
    });
  </script>
