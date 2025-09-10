<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Report;
use App\Models\PostReport;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // User metrics
        $totalUsers = User::count();
        $userChange = $this->calculateChange(User::class);
        $usersChartData = $this->getWeeklyData(User::class);

        // Post metrics
        $totalPosts = Post::count();
        $postChange = $this->calculateChange(Post::class);
        $postsChartData = $this->getWeeklyData(Post::class);

        // Report metrics
        $totalReports = Report::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $reportChange = $this->calculateChange(Report::class, true);
        $reportsChartData = $this->getDailyReportData();

        // Engagement metrics
        $engagementRate = $this->calculateEngagementRate();
        $engagementChange = $this->calculateEngagementChange();
        $engagementData = $this->getEngagementData();

        return view('adminPages.admincontent', compact(
            'totalUsers',
            'userChange',
            'usersChartData',
            'totalPosts',
            'postChange',
            'postsChartData',
            'totalReports',
            'reportChange',
            'reportsChartData',
            'engagementRate',
            'engagementChange',
            'engagementData'
        ));
    }

    private function calculateChange($model, $weekly = false)
    {
        $currentPeriod = $weekly
            ? $model::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count()
            : $model::whereMonth('created_at', now())->count();

        $previousPeriod = $weekly
            ? $model::whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])->count()
            : $model::whereMonth('created_at', now()->subMonth())->count();

        if ($previousPeriod == 0) return 0;

        return round((($currentPeriod - $previousPeriod) / $previousPeriod) * 100, 1);
    }

    private function getWeeklyData($model)
    {
        $labels = [];
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('D');
            $data[] = $model::whereDate('created_at', $date)->count();
        }

        return ['labels' => $labels, 'data' => $data];
    }

    private function getDailyReportData()
    {
        $labels = [];
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('D');
            $data[] = Report::whereDate('created_at', $date)->count();
        }

        return ['labels' => $labels, 'data' => $data];
    }

    private function calculateEngagementRate()
    {
        $activeUsers = User::where('last_active_at', '>=', now()->subDay())->count();
        $totalUsers = User::count();

        return $totalUsers > 0 ? round(($activeUsers / $totalUsers) * 100, 1) : 0;
    }

    private function calculateEngagementChange()
    {
        $current = $this->calculateEngagementRate();
        $previous = User::where('last_active_at', '>=', now()->subWeek()->subDay())
            ->where('last_active_at', '<', now()->subDay())
            ->count() / max(User::count(), 1) * 100;

        return round((($current - $previous) / max($previous, 1)) * 100, 1);
    }

    private function getEngagementData()
    {
        return [
            'active' => User::where('last_active_at', '>=', now()->subDay())->count(),
            'passive' => User::where('last_active_at', '<', now()->subDay())
                ->where('last_active_at', '>=', now()->subWeek())->count(),
            'new' => User::where('created_at', '>=', now()->subWeek())->count()
        ];
    }

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin'); // Assumes you have an admin middleware
    }

    public function promote(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $user = User::find($request->user_id);

        if ($user->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'User is already an admin'
            ]);
        }

        $user->is_admin = true;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User promoted to admin successfully'
        ]);
    }

    public function showReport($id)
    {
        $report = Report::findOrFail($id);
        return view('admin.reports.show', compact('report'));
    }

    public function showPostReport($id)
    {
        $postReport = PostReport::findOrFail($id);
        return view('admin.post-reports.show', compact('postReport'));
    }
}
