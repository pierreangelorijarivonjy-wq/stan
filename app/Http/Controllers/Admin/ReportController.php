<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\MessageReport;
use App\Models\Message;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $reports = MessageReport::with(['user', 'message.sender'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.reports.index', compact('reports'));
    }

    public function resolve(MessageReport $report)
    {
        $report->update(['status' => 'resolved']);
        return redirect()->back()->with('success', 'Signalement marqué comme résolu.');
    }

    public function dismiss(MessageReport $report)
    {
        $report->update(['status' => 'dismissed']);
        return redirect()->back()->with('success', 'Signalement rejeté.');
    }

    public function destroyMessage(MessageReport $report)
    {
        $report->message->update(['is_deleted' => true, 'content' => 'Message supprimé par la modération.']);
        $report->update(['status' => 'resolved']);

        return redirect()->back()->with('success', 'Message supprimé et signalement résolu.');
    }
}
