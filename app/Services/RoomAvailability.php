<?php

namespace App\Services;

use App\Models\BookingRoom;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class RoomAvailability
{
    public function forRooms(
        Collection $rooms,
        Carbon|string $checkIn,
        Carbon|string $checkOut,
        ?int $excludeBookingId = null
    ): array
    {
        $checkIn = Carbon::parse($checkIn)->startOfDay();
        $checkOut = Carbon::parse($checkOut)->startOfDay();
        $roomIds = $rooms->pluck('id')->all();

        if ($roomIds === [] || ! $checkOut->greaterThan($checkIn)) {
            return [];
        }

        $nights = [];
        for ($date = $checkIn->copy(); $date->lt($checkOut); $date->addDay()) {
            $nights[$date->toDateString()] = [
                'booked' => 0,
                'reserved' => 0,
            ];
        }

        $allocations = BookingRoom::query()
            ->whereIn('room_id', $roomIds)
            ->whereHas('booking', function ($query) use ($checkIn, $checkOut, $excludeBookingId): void {
                if ($excludeBookingId !== null) {
                    $query->whereKeyNot($excludeBookingId);
                }
                $query->where('check_in', '<', $checkOut->toDateString())
                    ->where('check_out', '>', $checkIn->toDateString())
                    ->where(function ($statusQuery): void {
                        $statusQuery->where(function ($confirmedQuery): void {
                            $confirmedQuery->where('booking_status', 'confirmed')
                                ->where('payment_status', 'paid');
                        })->orWhere(function ($reservationQuery): void {
                            $reservationQuery->where('booking_status', 'pending')
                                ->where('payment_status', 'pending')
                                ->where('hold_expires_at', '>', now());
                        });
                    });
            })
            ->with('booking:id,check_in,check_out,booking_status,payment_status,hold_expires_at')
            ->get(['id', 'booking_id', 'room_id', 'quantity']);

        $roomNightCounts = [];
        foreach ($roomIds as $roomId) {
            $roomNightCounts[$roomId] = $nights;
        }

        foreach ($allocations as $allocation) {
            $booking = $allocation->booking;
            if (! $booking?->check_in || ! $booking?->check_out) {
                continue;
            }

            $countKey = $booking->booking_status === 'confirmed' && $booking->payment_status === 'paid'
                ? 'booked'
                : 'reserved';
            $occupiedFrom = Carbon::parse($booking->check_in)->startOfDay()->max($checkIn);
            $occupiedUntil = Carbon::parse($booking->check_out)->startOfDay()->min($checkOut);

            for ($date = $occupiedFrom->copy(); $date->lt($occupiedUntil); $date->addDay()) {
                $dateKey = $date->toDateString();
                $roomNightCounts[$allocation->room_id][$dateKey][$countKey] += (int) $allocation->quantity;
            }
        }

        $result = [];
        foreach ($rooms as $room) {
            $total = max(0, (int) $room->total_rooms);
            $daily = [];

            foreach ($roomNightCounts[$room->id] as $date => $counts) {
                $daily[] = [
                    'date' => $date,
                    'total' => $total,
                    'booked' => $counts['booked'],
                    'reserved' => $counts['reserved'],
                    'available' => $room->status
                        ? max(0, $total - $counts['booked'] - $counts['reserved'])
                        : 0,
                ];
            }

            $result[$room->id] = [
                'total' => $total,
                'daily' => $daily,
                'booked' => $daily === [] ? 0 : max(array_column($daily, 'booked')),
                'reserved' => $daily === [] ? 0 : max(array_column($daily, 'reserved')),
                'available' => $daily === [] ? $total : min(array_column($daily, 'available')),
            ];
        }

        return $result;
    }
}
