<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /**
     * Display the settings form.
     */
    public function edit()
    {
        // Retrieve the first settings record (or create a new one if it doesn't exist)
        $settings = Setting::firstOrNew();

        return view('admin.settings.edit', compact('settings'));
    }

    /**
     * Update the settings.
     */
    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image upload
            'owner_email' => 'required|email',
            'color_theme' => 'required|in:light,dark',
            'social_links' => 'nullable|json',
        ]);

        $settings = Setting::firstOrNew();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            $settings->logo_path = $logoPath;
        } elseif ($request->has('logo_path')) {
            $settings->logo_path = $request->logo_path; // Fallback to manual path
        }

        $settings->fill($request->only([
            'site_name',
            'owner_email',
            'color_theme',
            'social_links',
        ]));
        $settings->save();

        return redirect()->route('admin.settings.edit')
            ->with('success', 'Settings updated successfully!');
    }
}
