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
        \Log::info('QuickResponse Store: Request received', $request->all());
        
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'value' => 'required|string|max:255',
                'type' => 'required|in:welcome,general',
                'order' => 'required|integer|min:0'
            ]);

            \Log::info('QuickResponse Store: Validation passed', $validated);

            // Set is_active based on checkbox presence (checkbox sends "on" when checked, nothing when unchecked)
            $validated['is_active'] = $request->has('is_active') ? true : false;

            $quickResponse = QuickResponse::create($validated);
            
            \Log::info('QuickResponse Store: Created successfully', ['id' => $quickResponse->id]);

            return redirect()->route('quick-responses.index')
                ->with('success', 'Quick Response berhasil ditambahkan!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('QuickResponse Store Validation Error:', [
                'errors' => $e->errors()
            ]);
            
            return back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('QuickResponse Store Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()
                ->withInput()
                ->with('error', 'Gagal menambahkan Quick Response: ' . $e->getMessage());
        }
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
        \Log::info('QuickResponse Update: Request received', [
            'id' => $quickResponse->id,
            'data' => $request->all()
        ]);
        
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'value' => 'required|string|max:255',
                'type' => 'required|in:welcome,general',
                'order' => 'required|integer|min:0'
            ]);

            // Set is_active based on checkbox presence (checkbox sends "on" when checked, nothing when unchecked)
            $validated['is_active'] = $request->has('is_active') ? true : false;

            $quickResponse->update($validated);
            
            \Log::info('QuickResponse Update: Updated successfully', ['id' => $quickResponse->id]);

            return redirect()->route('quick-responses.index')
                ->with('success', 'Quick Response berhasil diupdate!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('QuickResponse Update Validation Error:', [
                'errors' => $e->errors()
            ]);
            
            return back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('QuickResponse Update Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()
                ->withInput()
                ->with('error', 'Gagal mengupdate Quick Response: ' . $e->getMessage());
        }
    }

    public function destroy(QuickResponse $quickResponse)
    {
        $quickResponse->delete();

        return redirect()->route('quick-responses.index')
            ->with('success', 'Quick Response berhasil dihapus!');
    }

    // Update order via drag and drop
    public function updateOrder(Request $request)
    {
        try {
            $order = $request->input('order');
            
            foreach ($order as $item) {
                QuickResponse::where('id', $item['id'])
                    ->update(['order' => $item['order']]);
            }
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('QuickResponse UpdateOrder Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // Toggle active/inactive
    public function toggle(Request $request, $id)
    {
        try {
            $quickResponse = QuickResponse::findOrFail($id);
            $quickResponse->update([
                'is_active' => $request->input('is_active', false)
            ]);
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('QuickResponse Toggle Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
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
