<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SportRequest;
use App\Models\Sport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SportController extends Controller
{
    /**
     * Menampilkan daftar semua cabang olahraga.
     */
    public function index(): View
    {
        $sports = Sport::withCount('courts')
            ->latest('id')
            ->paginate(10);

        return view('admin.sports.index', compact('sports'));
    }

    /**
     * Menampilkan form untuk menambahkan olahraga baru.
     */
    public function create(): View
    {
        return view('admin.sports.create');
    }

    /**
     * Menyimpan data olahraga baru ke database.
     */
    public function store(SportRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['name']);

        Sport::create($validated);

        return redirect()
            ->route('admin.sports.index')
            ->with('success', 'Cabang olahraga berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail data olahraga (dialihkan ke halaman edit).
     */
    public function show(Sport $sport): RedirectResponse
    {
        return redirect()->route('admin.sports.edit', $sport);
    }

    /**
     * Menampilkan form edit cabang olahraga.
     */
    public function edit(Sport $sport): View
    {
        return view('admin.sports.edit', compact('sport'));
    }

    /**
     * Memperbarui data cabang olahraga di database.
     */
    public function update(SportRequest $request, Sport $sport): RedirectResponse
    {
        $validated = $request->validated();
        $validated['slug'] = Str::slug($validated['name']);

        $sport->update($validated);

        return redirect()
            ->route('admin.sports.index')
            ->with('success', 'Data cabang olahraga berhasil diperbarui.');
    }

    /**
     * Menghapus cabang olahraga dari database.
     */
    public function destroy(Sport $sport): RedirectResponse
    {
        // Proteksi integritas relasi: periksa apakah ada lapangan terkait
        if ($sport->courts()->exists()) {
            return redirect()
                ->route('admin.sports.index')
                ->with('error', 'Cabang olahraga tidak dapat dihapus karena masih digunakan oleh data lapangan yang ada.');
        }

        $sport->delete();

        return redirect()
            ->route('admin.sports.index')
            ->with('success', 'Cabang olahraga berhasil dihapus.');
    }
}
