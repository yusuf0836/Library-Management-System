<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\BookIssue;
use App\Models\Fine;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        /*
         * Member dashboard statistics.
         */
        if ($user->role === 'member') {
            $member = $user->member;

            $activeBorrowings = $member
                ? BookIssue::where('member_id', $member->id)
                    ->whereNull('returned_at')
                    ->count()
                : 0;

            $overdueBorrowings = $member
                ? BookIssue::where('member_id', $member->id)
                    ->whereNull('returned_at')
                    ->whereDate('due_at', '<', Carbon::today())
                    ->count()
                : 0;

            $unpaidFine = $member
                ? Fine::whereHas('issue', function ($query) use ($member) {
                    $query->where('member_id', $member->id);
                })
                    ->whereIn('status', ['unpaid', 'partial'])
                    ->selectRaw('COALESCE(SUM(amount - paid_amount), 0) as total')
                    ->value('total')
                : 0;

            return view('dashboard.index', compact(
                'activeBorrowings',
                'overdueBorrowings',
                'unpaidFine'
            ));
        }

        /*
         * Admin and Librarian dashboard statistics.
         */
        $totalBooks = Book::count();

        $availableCopies = BookCopy::where('status', 'available')->count();

        $issuedCopies = BookCopy::where('status', 'issued')->count();

        $activeMembers = Member::where('is_active', true)->count();

        $overdueIssues = BookIssue::whereNull('returned_at')
            ->whereDate('due_at', '<', Carbon::today())
            ->count();

        $outstandingFine = Fine::whereIn('status', ['unpaid', 'partial'])
            ->selectRaw('COALESCE(SUM(amount - paid_amount), 0) as total')
            ->value('total');

        $recentIssues = BookIssue::with([
            'member.user',
            'copy.book',
        ])
            ->latest()
            ->take(6)
            ->get();


        $copyStatusStats = BookCopy::select(
            'status',
            DB::raw('COUNT(*) as total')
        )
            ->groupBy('status')
            ->pluck('total', 'status');

        $chartStatuses = [
            'available',
            'issued',
            'reserved',
            'lost',
            'damaged',
        ];

        $copyStatusLabels = collect($chartStatuses)
            ->map(fn ($status) => ucfirst($status))
            ->toArray();

        $copyStatusData = collect($chartStatuses)
            ->map(fn ($status) => $copyStatusStats[$status] ?? 0)
            ->toArray();

        $monthlyIssueData = BookIssue::select(
            DB::raw("DATE_FORMAT(issued_at, '%Y-%m') as month"),
            DB::raw('COUNT(*) as total')
        )
            ->whereDate('issued_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $monthlyIssueLabels = [];
        $monthlyIssueCounts = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);

            $monthKey = $date->format('Y-m');

            $monthlyIssueLabels[] = $date->format('M Y');

            $monthlyIssueCounts[] = $monthlyIssueData[$monthKey] ?? 0;
        }
        return view('dashboard.index', compact(
            'totalBooks',
            'availableCopies',
            'issuedCopies',
            'activeMembers',
            'overdueIssues',
            'outstandingFine',
            'recentIssues',
            'copyStatusLabels',
            'copyStatusData',
            'monthlyIssueLabels',
            'monthlyIssueCounts',
        ));
    }
}