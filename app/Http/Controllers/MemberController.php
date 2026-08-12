<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $members = Member::with('user')
            ->when($search, function ($query, $search) {
                $query->where(function ($memberQuery) use ($search) {
                    $memberQuery->where('member_code', 'like', '%' . $search . '%')
                        ->orWhere('phone', 'like', '%' . $search . '%')
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', '%' . $search . '%')
                                ->orWhere('email', 'like', '%' . $search . '%');
                        });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('members.index', compact('members', 'search'));
    }

    public function create()
    {
        return view('members.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'member_code' => ['required', 'string', 'max:50', 'unique:members,member_code'],
            'phone' => ['nullable', 'string', 'max:30'],
            'department' => ['nullable', 'string', 'max:150'],
            'address' => ['nullable', 'string', 'max:255'],
            'joined_at' => ['required', 'date'],
            'is_active' => ['required', 'boolean'],
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'member',
            ]);

            Member::create([
                'user_id' => $user->id,
                'member_code' => $validated['member_code'],
                'phone' => $validated['phone'] ?? null,
                'department' => $validated['department'] ?? null,
                'address' => $validated['address'] ?? null,
                'joined_at' => $validated['joined_at'],
                'is_active' => $validated['is_active'],
            ]);
        });

        return redirect()
            ->route('members.index')
            ->with('success', 'Member added successfully.');
    }

    public function edit(Member $member)
    {
        $member->load('user');

        return view('members.edit', compact('member'));
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('users', 'email')->ignore($member->user_id),
            ],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
            'member_code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('members', 'member_code')->ignore($member->id),
            ],
            'phone' => ['nullable', 'string', 'max:30'],
            'department' => ['nullable', 'string', 'max:150'],
            'address' => ['nullable', 'string', 'max:255'],
            'joined_at' => ['required', 'date'],
            'is_active' => ['required', 'boolean'],
        ]);

        DB::transaction(function () use ($validated, $member) {
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
            ];

            if (! empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $member->user->update($userData);

            $member->update([
                'member_code' => $validated['member_code'],
                'phone' => $validated['phone'] ?? null,
                'department' => $validated['department'] ?? null,
                'address' => $validated['address'] ?? null,
                'joined_at' => $validated['joined_at'],
                'is_active' => $validated['is_active'],
            ]);
        });

        return redirect()
            ->route('members.index')
            ->with('success', 'Member updated successfully.');
    }

    public function destroy(Member $member)
    {
        if ($member->issues()->exists()) {
            return redirect()
                ->route('members.index')
                ->with('error', 'This member cannot be deleted because the member has borrowing history.');
        }

        $member->user->delete();

        return redirect()
            ->route('members.index')
            ->with('success', 'Member deleted successfully.');
    }
}