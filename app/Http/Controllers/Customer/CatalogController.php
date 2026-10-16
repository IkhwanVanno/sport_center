<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    /**
     * Tampilkan halaman utama katalog / beranda publik.
     */
    public function index(Request $request): View
    {
        return view('public.home');
    }

    /**
     * Tampilkan daftar lapangan.
     */
    public function list(Request $request): View
    {
        return view('public.courts.index');
    }

    /**
     * Tampilkan detail lapangan tertentu.
     */
    public function show(Request $request, string $slug): View
    {
        return view('public.courts.show');
    }

    /**
     * Cek ketersediaan slot lapangan.
     */
    public function checkAvailability(Request $request, $court): JsonResponse
    {
        return response()->json(['available' => true]);
    }
}
