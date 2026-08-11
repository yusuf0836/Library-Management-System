<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    private array $defaults = [
        'library_name' => 'Library Management System',
        'library_address' => '',
        'library_phone' => '',
        'library_email' => '',
        'default_borrowing_days' => '14',
        'fine_per_day' => '5',
    ];

    /**
     * Show system settings page.
     */
    public function index()
    {
        foreach ($this->defaults as $key => $value) {
            Setting::firstOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        $settings = Setting::pluck('value', 'key');

        return view('settings.index', compact('settings'));
    }

    /**
     * Save system settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'library_name' => ['required', 'string', 'max:150'],
            'library_address' => ['nullable', 'string', 'max:255'],
            'library_phone' => ['nullable', 'string', 'max:30'],
            'library_email' => ['nullable', 'email', 'max:150'],
            'default_borrowing_days' => ['required', 'integer', 'min:1', 'max:365'],
            'fine_per_day' => ['required', 'numeric', 'min:0', 'max:10000'],
        ]);

        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()
            ->route('settings.index')
            ->with('success', 'System settings updated successfully.');
    }
}