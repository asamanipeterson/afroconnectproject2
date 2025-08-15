<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\PostReport;
use App\Models\User;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function store(Request $request, User $user)
    {
        $request->validate([
            'reason' => 'required|in:harassment,spam,inappropriate,fake_account,hate_speech,other',
            'details' => 'required_if:reason,other|string|max:1000|nullable',
        ]);

        Report::create([
            'reported_user_id' => $user->id,
            'reporter_id' => Auth::id(),
            'reason' => $request->input('reason'),
            'details' => $request->input('details'),
            'status' => 'pending',
        ]);

        $user->increment('reports_count');

        return redirect()->back()->with('success', 'User report submitted successfully.');
    }

    public function storePost(Request $request, Post $post)
    {
        $request->validate([
            'reason' => 'required|in:harassment,spam,inappropriate,hate_speech,other',
            'details' => 'required_if:reason,other|string|max:1000|nullable',
        ]);

        PostReport::create([
            'post_id' => $post->id,
            'reporter_id' => Auth::id(),
            'reason' => $request->input('reason'),
            'details' => $request->input('details'),
            'status' => 'pending',
        ]);

        $post->increment('reports_count');

        return redirect()->back()->with('success', 'Post report submitted successfully.');
    }

    public function index()
    {
        $userReports = Report::with(['reportedUser', 'reporter'])->get();
        $postReports = PostReport::with(['post.user', 'reporter'])->get();
        return view('Admin.reports.index', compact('userReports', 'postReports'));
    }

    public function resolve(Request $request, Report $report)
    {
        $report->update(['status' => 'resolved']);
        return redirect()->route('admin.reports.index')->with('success', 'User report resolved successfully.');
    }

    public function resolvePost(Request $request, PostReport $report)
    {
        $report->update(['status' => 'resolved']);
        return redirect()->route('admin.reports.index')->with('success', 'Post report resolved successfully.');
    }
}
