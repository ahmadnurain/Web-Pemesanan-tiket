<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Destinations;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    public function index(Request $request)
    {
        // MIN/MAX global untuk slider & validasi filter harga
        $globalMin = (int) (Destinations::min('ticket_price') ?? 0);
        $globalMax = (int) (Destinations::max('ticket_price') ?? 0);

        // Ambil filter aktif dari session (URL bersih)
        $filters = (array) session('dest.filters', []);

        // Sanitisasi sekali lagi (jaga-jaga)
        $filters = $this->sanitizeFilters($filters, $globalMin, $globalMax);

        // Bangun query
        $query = Destinations::with(['photos', 'category'])
            ->withCount('transactions');

        // q
        if (!empty($filters['q'])) {
            $q = (string) $filters['q'];
            $query->where(function ($qB) use ($q) {
                $qB->where('name', 'like', "%{$q}%")
                    ->orWhere('location', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        // category[]
        if (!empty($filters['category']) && is_array($filters['category'])) {
            $query->whereIn('category_id', $filters['category']);
        }

        // location
        if (!empty($filters['location'])) {
            $query->where('location', (string) $filters['location']);
        }

        // price_max
        if (!empty($filters['price_max'])) {
            $query->where('ticket_price', '<=', (int) $filters['price_max']);
        }

        // sort
        $sort = $filters['sort'] ?? 'newest';
        switch ($sort) {
            case 'cheapest':
                $query->orderBy('ticket_price', 'asc');
                break;
            case 'popular':
                $query->orderBy('transactions_count', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // per_page
        $perPage = (int) ($filters['per_page'] ?? 9);
        if ($perPage <= 0 || $perPage > 48) $perPage = 9;

        // Penting: pagination TANPA appends apa pun → URL bersih (kecuali ?page)
        $destinations = $query->paginate($perPage);

        // Data sidebar (global)
        $locations = Destinations::query()
            ->whereNotNull('location')
            ->select('location')->distinct()->orderBy('location')
            ->pluck('location');

        $categories = Category::orderBy('name')->get();

        return view('destinations', [
            'destinations' => $destinations,
            'minPrice'     => $globalMin,
            'maxPrice'     => $globalMax,
            'locations'    => $locations,
            'categories'   => $categories,
            'sort'         => $sort,
            // Kirim juga filters agar input terisi ulang
            'filters'      => $filters,
        ]);
    }

    public function apply(Request $request)
    {
        // MIN/MAX global untuk validasi price_max
        $globalMin = (int) (Destinations::min('ticket_price') ?? 0);
        $globalMax = (int) (Destinations::max('ticket_price') ?? 0);

        // Ambil input dari POST (tanpa query di URL)
        $raw = $request->only(['q', 'category', 'location', 'price_max', 'sort', 'per_page']);

        // Sanitasi & simpan ke session
        $filters = $this->sanitizeFilters($raw, $globalMin, $globalMax);
        session(['dest.filters' => $filters]);

        // Redirect ke index TANPA query → URL bersih
        return redirect()->route('destinations.index');
    }

    public function reset()
    {
        session()->forget('dest.filters');
        return redirect()->route('destinations.index');
    }

    /**
     * Hanya simpan nilai yang "bermakna" (bukan default/kosong).
     */
    private function sanitizeFilters(array $raw, int $globalMin, int $globalMax): array
    {
        $out = [];

        // q
        if (!empty($raw['q']) && is_string($raw['q']) && trim($raw['q']) !== '') {
            $out['q'] = trim($raw['q']);
        }

        // category[]
        if (!empty($raw['category'])) {
            $cats = array_values(array_filter((array) $raw['category'], fn($x) => (string)$x !== ''));
            if (!empty($cats)) $out['category'] = $cats;
        }

        // location
        if (!empty($raw['location']) && is_string($raw['location'])) {
            $out['location'] = trim($raw['location']);
        }

        // price_max: simpan hanya jika benar-benar membatasi (di bawah max global)
        if (isset($raw['price_max'])) {
            $p = (int) $raw['price_max'];
            if ($p > $globalMin && $p < $globalMax) {
                $out['price_max'] = $p;
            }
        }

        // sort: sembunyikan default 'newest'
        if (!empty($raw['sort']) && in_array($raw['sort'], ['newest', 'cheapest', 'popular'], true)) {
            if ($raw['sort'] !== 'newest') $out['sort'] = $raw['sort'];
        }

        // per_page: sembunyikan default 9
        if (!empty($raw['per_page'])) {
            $pp = max(1, min(48, (int) $raw['per_page']));
            if ($pp !== 9) $out['per_page'] = $pp;
        }

        return $out;
    }

    public function show(Destinations $destination)
    {
        $destination->load(['photos', 'category']);

        $similar = Destinations::with('photos')
            ->where('id', '!=', $destination->id)
            ->when($destination->category_id, fn($q) => $q->where('category_id', $destination->category_id))
            ->latest()
            ->limit(6)
            ->get();

        return view('destination-detail', [
            'destination' => $destination,
            'similarDestinations' => $similar,
        ]);
    }
}
