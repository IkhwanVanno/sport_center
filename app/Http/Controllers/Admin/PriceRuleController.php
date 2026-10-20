<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PriceRuleRequest;
use App\Models\CourtPriceRule;
use App\Services\PriceRuleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PriceRuleController extends Controller
{
    public function __construct(
        protected PriceRuleService $priceRuleService
    ) {}

    /**
     * Menampilkan daftar aturan tarif dengan scoping cabang.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', CourtPriceRule::class);

        $courtId = $request->filled('court_id') ? (int) $request->query('court_id') : null;

        $priceRules = $this->priceRuleService->listPriceRules($request->user(), $courtId, 10);
        $courts = $this->priceRuleService->getCourtOptions($request->user());

        return view('admin.price-rules.index', compact('priceRules', 'courts', 'courtId'));
    }

    /**
     * Menampilkan form penambahan aturan tarif khusus.
     */
    public function create(Request $request): View
    {
        Gate::authorize('create', CourtPriceRule::class);

        $courts = $this->priceRuleService->getCourtOptions($request->user());

        return view('admin.price-rules.create', compact('courts'));
    }

    /**
     * Menyimpan aturan tarif baru via service layer.
     */
    public function store(PriceRuleRequest $request): RedirectResponse
    {
        $this->priceRuleService->storePriceRule($request->user(), $request->validated());

        return redirect()
            ->route('admin.price-rules.index')
            ->with('success', 'Aturan tarif berhasil ditambahkan.');
    }

    /**
     * Menghapus aturan tarif.
     * Mencegah IDOR: Gate::authorize('delete', $priceRule).
     */
    public function destroy(CourtPriceRule $priceRule): RedirectResponse
    {
        Gate::authorize('delete', $priceRule);

        $this->priceRuleService->deletePriceRule($priceRule);

        return redirect()
            ->route('admin.price-rules.index')
            ->with('success', 'Aturan tarif berhasil dihapus.');
    }
}
