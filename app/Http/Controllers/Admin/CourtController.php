<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Court;
use App\Models\Facility;
use App\Models\Sport;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourtController extends Controller
{
    public function index()
    {
        $courts = Court::with(['branch', 'sport'])->latest()->paginate(10);
        return view('admin.courts.index', compact('courts'));
    }

    public function create()
    {
        $branches = Branch::where('is_active', true)->get();
        $sports = Sport::all();
        $facilities = Facility::all();
        return view('admin.courts.create', compact('branches', 'sports', 'facilities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'sport_id' => 'required|exists:sports,id',
            'name' => 'required|string|max:100',
            'base_price_per_hour' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'facilities' => 'array|nullable',
        ]);

        $validated['slug'] = Str::slug($request->name);
        $court = Court::create($validated);
        $court->facilities()->sync($validated['facilities'] ?? []);

        return redirect()->route('admin.courts.index')->with('success', 'Lapangan berhasil ditambahkan.');
    }

    public function edit(Court $court)
    {
        $branches = Branch::where('is_active', true)->get();
        $sports = Sport::all();
        $facilities = Facility::all();
        $court->load('facilities');
        return view('admin.courts.edit', compact('court', 'branches', 'sports', 'facilities'));
    }

    public function update(Request $request, Court $court)
    {
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'sport_id' => 'required|exists:sports,id',
            'name' => 'required|string|max:100',
            'base_price_per_hour' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'facilities' => 'array|nullable',
        ]);

        $validated['slug'] = Str::slug($request->name);
        $court->update($validated);
        $court->facilities()->sync($validated['facilities'] ?? []);

        return redirect()->route('admin.courts.index')->with('success', 'Data lapangan berhasil diperbarui.');
    }

    public function destroy(Court $court)
    {
        $court->facilities()->detach();
        $court->delete();
        return redirect()->route('admin.courts.index')->with('success', 'Lapangan berhasil dihapus.');
    }
}
