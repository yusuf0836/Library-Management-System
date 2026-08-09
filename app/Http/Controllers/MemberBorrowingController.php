<?php

namespace App\Http\Controllers;

use App\Models\BookIssue;

class MemberBorrowingController extends Controller
{
    public function index()
    {
        $member = auth()->user()->member;

        if (! $member) {
            abort(403, 'Member profile was not found.');
        }

        $issues = BookIssue::with([
            'copy.book',
            'fine',
        ])
            ->where('member_id', $member->id)
            ->latest()
            ->paginate(10);

        return view('member.borrowing-history', compact('issues'));
    }
}