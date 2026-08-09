<?php

namespace App\Http\Controllers;

use App\Models\Fine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FineController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status');

        $fines = Fine::with([
            'issue.member.user',
            'issue.copy.book',
        ])
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('fines.index', compact('fines', 'status'));
    }

    public function pay(Request $request, Fine $fine)
    {
        if (in_array($fine->status, ['paid', 'waived'])) {
            return redirect()
                ->route('fines.index')
                ->with('error', 'This fine has already been completed.');
        }

        $remainingAmount = $fine->amount - $fine->paid_amount;

        $validated = $request->validate([
            'payment_amount' => [
                'required',
                'numeric',
                'min:0.01',
                'max:' . $remainingAmount,
            ],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($fine, $validated) {
            $fine->refresh();

            $newPaidAmount = $fine->paid_amount + $validated['payment_amount'];

            $isFullyPaid = $newPaidAmount >= $fine->amount;

            $fine->update([
                'paid_amount' => $newPaidAmount,
                'status' => $isFullyPaid ? 'paid' : 'partial',
                'paid_at' => $isFullyPaid ? now()->toDateString() : null,
                'notes' => $validated['notes'] ?? $fine->notes,
            ]);
        });

        return redirect()
            ->route('fines.index')
            ->with('success', 'Fine payment recorded successfully.');
    }
}