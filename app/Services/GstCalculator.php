<?php

namespace App\Services;

use App\Models\HotelGstSlab;
use RuntimeException;

class GstCalculator
{
    public function hotelSlabFor(int $tariffPaise): HotelGstSlab
    {
        $tariff = $tariffPaise / 100;
        $slab = HotelGstSlab::query()
            ->where('min_tariff', '<=', $tariff)
            ->where(function ($query) use ($tariff): void {
                $query->whereNull('max_tariff')->orWhere('max_tariff', '>=', $tariff);
            })
            ->orderBy('min_tariff')
            ->first();

        if (! $slab) {
            throw new RuntimeException('No hotel GST slab matches this room tariff.');
        }

        return $slab;
    }

    public function summarize(array $lines, string $category): array
    {
        $basePaise = array_sum(array_column($lines, 'base_paise'));
        $gstPaise = array_sum(array_column($lines, 'gst_paise'));

        return [
            'base_paise' => $basePaise,
            'gst_paise' => $gstPaise,
            'total_paise' => $basePaise + $gstPaise,
            'gst_percentage' => $basePaise > 0
                ? round(($gstPaise / $basePaise) * 100, 2)
                : 0,
            'gst_category' => $category,
            'lines' => array_map(static function (array $line): array {
                unset($line['base_paise'], $line['gst_paise']);

                return $line;
            }, $lines),
        ];
    }

    public function hotelLine(
        int $unitPricePaise,
        int $quantity,
        int $nights,
        string $description,
        ?int $roomId = null,
        ?string $roomType = null
    ): array {
        $slab = $this->hotelSlabFor($unitPricePaise);
        $basePaise = $unitPricePaise * $quantity * $nights;
        $rate = (float) $slab->gst_percentage;

        return [
            'room_id' => $roomId,
            'description' => $description,
            'room_type' => $roomType,
            'quantity' => $quantity,
            'nights' => $nights,
            'unit_price' => $unitPricePaise / 100,
            'base_amount' => $basePaise / 100,
            'gst_category' => 'hotel_accommodation_tariff',
            'gst_percentage' => $rate,
            'gst_amount' => (int) round($basePaise * $rate / 100) / 100,
            'base_paise' => $basePaise,
            'gst_paise' => (int) round($basePaise * $rate / 100),
        ];
    }

    public function packageLine(
        int $unitPricePaise,
        int $guestCount,
        float $rate,
        string $category,
        string $description
    ): array {
        $basePaise = $unitPricePaise * $guestCount;
        $gstPaise = (int) round($basePaise * $rate / 100);

        return [
            'description' => $description,
            'quantity' => $guestCount,
            'unit_price' => $unitPricePaise / 100,
            'base_amount' => $basePaise / 100,
            'gst_category' => $category,
            'gst_percentage' => $rate,
            'gst_amount' => $gstPaise / 100,
            'base_paise' => $basePaise,
            'gst_paise' => $gstPaise,
        ];
    }
}
