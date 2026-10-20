<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;

class FacilitiesController extends Controller
{
    public function index()
    {
        $facilities = Facility::all();
        return view('admin.facilities.index', compact('facilities'));
    }

    public function create()
    {
        $facilities = Facility::all();
        return view('admin.facilities.create', compact('facilities'));
    }

    public function edit($id)
    {
        $facility = Facility::findOrFail($id);
        $facilities = Facility::all();
        return view('admin.facilities.edit', compact('facility', 'facilities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'icon' => 'nullable|string|max:255',
        ]);

        Facility::create($validated);
        return redirect()->route('admin.facilities.index')->with('success', 'Facility created successfully.');
    }

    public function update(Request $request, $id)
    {
        $facility = Facility::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'icon' => 'nullable|string|max:255',
        ]);

        $facility->update($validated);
        return redirect()->route('admin.facilities.index')->with('success', 'Facility updated successfully.');
    }

    public function destroy($id)
    {
        $facility = Facility::findOrFail($id);
        $facility->delete();
        return redirect()->route('admin.facilities.index')->with('success', 'Facility deleted successfully.');
    }
}
