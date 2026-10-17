<?php

namespace App\Http\Controllers\Admin;

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Court;
use App\Models\CourtPriceRule;
use Illuminate\Http\Request;

class PriceRuleController extends Controller
{
    public function index()
    {
        $priceRules = CourtPriceRule::with('court.branch')->latest()->paginate(10);
        return view('admin.price-rules.index', compact('priceRules'));
    }

    public function create()
    {
        $courts = Court::where('is_active', true)->with('branch')->get();
        return view('admin.price-rules.create', compact('courts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'court_id' => 'required|exists:courts,id',
            'day_of_week' => 'required|integer|between:0,6',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'price_per_hour' => 'required|numeric|min:0',
        ]);

        CourtPriceRule::create($validated);

        return redirect()->route('admin.price-rules.index')->with('success', 'Aturan tarif berhasil ditambahkan.');
    }

    public function destroy(CourtPriceRule $priceRule)
    {
        $priceRule->delete();
        return redirect()->route('admin.price-rules.index')->with('success', 'Aturan tarif berhasil dihapus.');
    }
}
