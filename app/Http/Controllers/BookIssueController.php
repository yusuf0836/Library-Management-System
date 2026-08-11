<?php

namespace App\Http\Controllers;

use App\Models\BookCopy;
use App\Models\BookIssue;
use App\Models\Fine;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;

class BookIssueController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->input('search');

        $issues = BookIssue::with([
            'member.user',
            'copy.book',
            'fine',
        ])
            ->when($search, function ($query, $search) {
                $query->where(function ($issueQuery) use ($search) {
                    $issueQuery->whereHas('member', function ($memberQuery) use ($search) {
                        $memberQuery->where('member_code', 'like', '%' . $search . '%')
                            ->orWhereHas('user', function ($userQuery) use ($search) {
                                $userQuery->where('name', 'like', '%' . $search . '%');
                            });
                    })
                    ->orWhereHas('copy.book', function ($bookQuery) use ($search) {
                        $bookQuery->where('title', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('copy', function ($copyQuery) use ($search) {
                        $copyQuery->where('accession_number', 'like', '%' . $search . '%');
                    });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();
            $finePerDay = (float) Setting::getValue('fine_per_day', 5);

        return view('book-issues.index', compact('issues', 'search', 'finePerDay'));
    }

    public function create()
    {
        $members = Member::with('user')
            ->where('is_active', true)
            ->orderBy('member_code')
            ->get();

        $copies = BookCopy::with('book')
            ->where('status', 'available')
            ->orderBy('accession_number')
            ->get();

        $defaultBorrowingDays = (int) Setting::getValue(
            'default_borrowing_days',
            14
        );

        return view(
            'book-issues.create',
            compact('members', 'copies', 'defaultBorrowingDays')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => ['required', 'exists:members,id'],
            'book_copy_id' => ['required', 'exists:book_copies,id'],
            'issued_at' => ['required', 'date'],
            'due_at' => ['required', 'date', 'after_or_equal:issued_at'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $member = Member::where('id', $validated['member_id'])
                    ->where('is_active', true)
                    ->firstOrFail();

                $copy = BookCopy::where('id', $validated['book_copy_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($copy->status !== 'available') {
                    throw new \Exception('This book copy is not available for issue.');
                }

                BookIssue::create([
                    'member_id' => $member->id,
                    'book_copy_id' => $copy->id,
                    'issued_by' => auth()->id(),
                    'issued_at' => $validated['issued_at'],
                    'due_at' => $validated['due_at'],
                    'status' => 'issued',
                    'notes' => $validated['notes'] ?? null,
                ]);

                $copy->update([
                    'status' => 'issued',
                ]);
            });

            return redirect()
                ->route('book-issues.index')
                ->with('success', 'Book issued successfully.');
        } catch (\Exception $exception) {
            return back()
                ->withInput()
                ->with('error', $exception->getMessage());
        }
    }

    public function returnBook(Request $request, BookIssue $bookIssue)
    {
        if ($bookIssue->status === 'returned') {
            return redirect()
                ->route('book-issues.index')
                ->with('error', 'This book has already been returned.');
        }

        $validated = $request->validate([
            'returned_at' => ['required', 'date', 'after_or_equal:' . $bookIssue->issued_at->format('Y-m-d')],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($validated, $bookIssue) {
            $returnedAt = Carbon::parse($validated['returned_at']);
            $dueAt = Carbon::parse($bookIssue->due_at);

            $lateDays = $returnedAt->greaterThan($dueAt)
                ? $dueAt->diffInDays($returnedAt)
                : 0;

            $finePerDay = (float) Setting::getValue('fine_per_day', 5);

            $fineAmount = $lateDays * $finePerDay;

            $bookIssue->update([
                'returned_at' => $returnedAt,
                'status' => 'returned',
                'notes' => $validated['notes'] ?? $bookIssue->notes,
            ]);

            $bookIssue->copy->update([
                'status' => 'available',
            ]);

            if ($fineAmount > 0) {
                Fine::updateOrCreate(
                    ['book_issue_id' => $bookIssue->id],
                    [
                        'amount' => $fineAmount,
                        'paid_amount' => 0,
                        'status' => 'unpaid',
                        'notes' => $lateDays . ' overdue day(s) × ৳' . $finePerDay,
                    ]
                );
            }
        });

        return redirect()
            ->route('book-issues.index')
            ->with('success', 'Book returned successfully.');
    }
}