<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\HotelGstSlab;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class GstSettingsController extends Controller
{
    public function index(): View
    {
        $slabs = HotelGstSlab::query()->orderBy('min_tariff')->get();
        $gstCollected = Booking::query()->where('payment_status', 'paid')->sum('gst_amount');

        return view('admin.gst-settings.index', compact('slabs', 'gstCollected'));
    }

    public function updateHotelSlabs(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'slabs' => ['required', 'array'],
            'slabs.*.min_tariff' => ['required', 'numeric', 'min:0'],
            'slabs.*.max_tariff' => ['nullable', 'numeric', 'min:0'],
            'slabs.*.gst_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        $slabs = HotelGstSlab::query()->orderBy('min_tariff')->get();
        if (count($validated['slabs']) !== $slabs->count()) {
            return back()->withErrors(['slabs' => 'The hotel GST slab list changed. Reload and try again.']);
        }

        $previousMaximumPaise = null;
        foreach ($slabs->values() as $index => $slab) {
            $values = $validated['slabs'][$slab->id] ?? null;
            if (! $values) {
                return back()->withErrors(['slabs' => 'A hotel GST slab is missing. Reload and try again.']);
            }

            $minimumPaise = (int) round((float) $values['min_tariff'] * 100);
            $maximumPaise = $values['max_tariff'] === null
                ? null
                : (int) round((float) $values['max_tariff'] * 100);

            if (
                ($index === 0 && $minimumPaise !== 0) ||
                ($previousMaximumPaise !== null && $minimumPaise !== $previousMaximumPaise + 1) ||
                ($maximumPaise !== null && $maximumPaise < $minimumPaise) ||
                ($index === $slabs->count() - 1 && $maximumPaise !== null) ||
                ($index < $slabs->count() - 1 && $maximumPaise === null)
            ) {
                return back()->withInput()->withErrors([
                    'slabs' => 'Slab thresholds must start at ₹0, be contiguous to the paisa, and leave the final upper limit open.',
                ]);
            }

            $previousMaximumPaise = $maximumPaise;
        }

        DB::transaction(function () use ($slabs, $validated): void {
            foreach ($slabs as $slab) {
                $slab->update($validated['slabs'][$slab->id]);
            }
        });

        return redirect()
            ->route('admin.gst-settings.index')
            ->with('success', 'Hotel GST slabs updated.');
    }
}
