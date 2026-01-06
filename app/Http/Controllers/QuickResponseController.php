<?php

namespace App\Http\Controllers;

use App\Models\QuickResponse;
use App\Models\Intent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuickResponseController extends Controller
{
    public function index()
    {
        $quickResponses = QuickResponse::orderBy('order')->get();
        
        // Ambil saran dari intent yang sering ditanyakan (top 10)
        $popularIntents = Intent::where('usage_count', '>', 0)
            ->where('is_fallback', false)
            ->orderBy('usage_count', 'desc')
            ->take(10)
            ->get(['display_name', 'usage_count']);
        
        return view('quick-responses.index', compact('quickResponses', 'popularIntents'));
    }

    public function create()
    {
        // Ambil saran dari intent yang sering ditanyakan
        $popularIntents = Intent::where('usage_count', '>', 0)
            ->where('is_fallback', false)
            ->orderBy('usage_count', 'desc')
            ->take(10)
            ->get(['display_name', 'usage_count']);
            
        return view('quick-responses.create', compact('popularIntents'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'value' => 'required|string|max:255',
            'type' => 'required|in:welcome,general',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');

        QuickResponse::create($validated);

        return redirect()->route('quick-responses.index')
            ->with('success', 'Quick Response berhasil ditambahkan!');
    }

    public function edit(QuickResponse $quickResponse)
    {
        // Ambil saran dari intent yang sering ditanyakan
        $popularIntents = Intent::where('usage_count', '>', 0)
            ->where('is_fallback', false)
            ->orderBy('usage_count', 'desc')
            ->take(10)
            ->get(['display_name', 'usage_count']);
            
        return view('quick-responses.edit', compact('quickResponse', 'popularIntents'));
    }

    public function update(Request $request, QuickResponse $quickResponse)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'value' => 'required|string|max:255',
            'type' => 'required|in:welcome,general',
            'order' => 'required|integer|min:0',
            'is_active' => 'boolean'
        ]);

        $validated['is_active'] = $request->has('is_active');

        $quickResponse->update($validated);

        return redirect()->route('quick-responses.index')
            ->with('success', 'Quick Response berhasil diupdate!');
    }

    public function destroy(QuickResponse $quickResponse)
    {
        $quickResponse->delete();

        return redirect()->route('quick-responses.index')
            ->with('success', 'Quick Response berhasil dihapus!');
    }

    // API untuk chatbot
    public function getQuickResponses(Request $request)
    {
        $type = $request->query('type', 'general'); // welcome atau general
        
        $quickResponses = QuickResponse::where('is_active', true)
            ->where('type', $type)
            ->orderBy('order')
            ->get(['title', 'value']);

        return response()->json([
            'quickResponses' => $quickResponses
        ]);
    }
}
