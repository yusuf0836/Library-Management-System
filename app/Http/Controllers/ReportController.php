<?php

namespace App\Http\Controllers;

use App\Models\BookIssue;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function overdue()
    {
        $overdueIssues = BookIssue::with([
            'member.user',
            'copy.book',
        ])
            ->whereNull('returned_at')
            ->whereDate('due_at', '<', Carbon::today())
            ->orderBy('due_at')
            ->paginate(20);

        return view('reports.overdue', compact('overdueIssues'));
    }
}