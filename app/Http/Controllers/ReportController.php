<?php

namespace App\Http\Controllers;

use App\Models\BookIssue;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display overdue book report.
     */
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

    /**
     * Display book issue and return circulation report.
     */
    public function circulation(Request $request)
    {
        $issues = $this->filteredIssues($request)
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('reports.circulation', compact('issues'));
    }

    /**
     * Download circulation report as CSV file.
     */
    public function exportCirculation(Request $request)
    {
        $issues = $this->filteredIssues($request)
            ->latest()
            ->get();

        $fileName = 'library-circulation-report-' . now()->format('Y-m-d-H-i-s') . '.csv';

        return response()->streamDownload(function () use ($issues) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Member Name',
                'Member Code',
                'Book Title',
                'Accession Number',
                'Issue Date',
                'Due Date',
                'Return Date',
                'Status',
                'Fine Amount',
                'Fine Status',
            ]);

            foreach ($issues as $issue) {
                $status = $this->getIssueStatus($issue);

                fputcsv($file, [
                    $issue->member->user->name,
                    $issue->member->member_code,
                    $issue->copy->book->title,
                    $issue->copy->accession_number,
                    $issue->issued_at->format('Y-m-d'),
                    $issue->due_at->format('Y-m-d'),
                    $issue->returned_at?->format('Y-m-d') ?? '',
                    $status,
                    $issue->fine?->amount ?? 0,
                    $issue->fine?->status ?? '',
                ]);
            }

            fclose($file);
        }, $fileName, [
            'Content-Type' => 'text/csv',
        ]);
    }

    /**
     * Apply date and status filters to circulation report.
     */
    private function filteredIssues(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $status = $request->input('status');

        return BookIssue::with([
            'member.user',
            'copy.book',
            'fine',
        ])
            ->when($startDate, function ($query, $startDate) {
                $query->whereDate('issued_at', '>=', $startDate);
            })
            ->when($endDate, function ($query, $endDate) {
                $query->whereDate('issued_at', '<=', $endDate);
            })
            ->when($status === 'issued', function ($query) {
                $query->whereNull('returned_at')
                    ->whereDate('due_at', '>=', now()->toDateString());
            })
            ->when($status === 'returned', function ($query) {
                $query->whereNotNull('returned_at');
            })
            ->when($status === 'overdue', function ($query) {
                $query->whereNull('returned_at')
                    ->whereDate('due_at', '<', now()->toDateString());
            });
    }

    /**
     * Return display status of a book issue.
     */
    private function getIssueStatus(BookIssue $issue): string
    {
        if ($issue->returned_at) {
            return 'Returned';
        }

        if (now()->startOfDay()->greaterThan($issue->due_at)) {
            return 'Overdue';
        }

        return 'Issued';
    }
}