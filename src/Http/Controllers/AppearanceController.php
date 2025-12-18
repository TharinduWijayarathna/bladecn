<?php

namespace BladeCN\BladeCN\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AppearanceController
{
    /**
     * Display the appearance settings form.
     */
    public function edit(Request $request)
    {
        // Check if settings/appearance view exists (installed in app), otherwise try package view
        if (view()->exists('settings.appearance')) {
            return view('settings.appearance');
        }
        
        return view('bladecn::settings.appearance');
    }

    /**
     * Update the appearance settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'appearance' => ['required', 'in:light,dark,system'],
        ]);

        // Store in session
        Session::put('appearance', $validated['appearance']);

        // Also set cookie for client-side access
        cookie()->queue('appearance', $validated['appearance'], 365 * 24 * 60); // 1 year

        // Handle JSON requests (from header dropdown)
        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'appearance' => $validated['appearance'],
            ]);
        }

        return back()->with('status', 'appearance-updated');
    }
}

