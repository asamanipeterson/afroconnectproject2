<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Report;
use App\Models\PostReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // User metrics
        $totalUsers = User::count();
        $userChange = $this->calculateChange(User::class);
        $usersPerDay = $this->getWeeklyData(User::class);

        // Post metrics
        $totalPosts = Post::count();
        $postChange = $this->calculateChange(Post::class);
        $postsPerDay = $this->getWeeklyData(Post::class);

        // Report metrics
        $totalReports = Report::count() + PostReport::count();
        $reportChange = $this->calculateCombinedReportChange();
        $reportsChartData = $this->getDailyCombinedReportData();

        // Engagement metrics
        $likesCount = DB::table('likes')->count();
        $commentsCount = DB::table('comments')->count();
        // Shares count is not available in the provided code/schema. We will exclude it.
        $engagementData = [
            'likes' => $likesCount,
            'comments' => $commentsCount,
        ];

        return view('adminPages.admincontent', compact(
            'totalUsers',
            'userChange',
            'usersPerDay',
            'totalPosts',
            'postChange',
            'postsPerDay',
            'totalReports',
            'reportChange',
            'reportsChartData',
            'engagementData',
            'likesCount', // Pass these for the blade view
            'commentsCount'
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

        if ($previousPeriod == 0) {
            return $currentPeriod > 0 ? 100 : 0;
        }

        return round((($currentPeriod - $previousPeriod) / $previousPeriod) * 100, 1);
    }

    private function getWeeklyData($model)
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $data[] = [
                'date' => $date->format('Y-m-d'),
                'count' => $model::whereDate('created_at', $date)->count(),
            ];
        }
        return $data;
    }

    private function getDailyCombinedReportData()
    {
        $labels = [];
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('D');

            $userReportsCount = Report::whereDate('created_at', $date)->count();
            $postReportsCount = PostReport::whereDate('created_at', $date)->count();

            $data[] = $userReportsCount + $postReportsCount;
        }

        return ['labels' => $labels, 'data' => $data];
    }

    private function calculateCombinedReportChange()
    {
        $currentWeekReports = Report::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count()
            + PostReport::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();

        $previousWeekReports = Report::whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])->count()
            + PostReport::whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])->count();

        if ($previousWeekReports == 0) {
            return $currentWeekReports > 0 ? 100 : 0;
        }

        return round((($currentWeekReports - $previousWeekReports) / $previousWeekReports) * 100, 1);
    }
}
