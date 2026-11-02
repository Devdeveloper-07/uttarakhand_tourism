@extends('frontend.layouts.app')

@section('title', 'Booking payment status')

@section('content')
    @php
        $isSuccess = $status === 'success';
        $isPending = $status === 'pending';
        $detailsUrl = $booking->item_type === 'hotel'
            ? route('hotel.details', $booking->item_id)
            : route('package.details', $booking->item_id);
    @endphp

    <section class="container py-5" style="min-height: 65vh;">
        <div class="mx-auto" style="max-width: 680px;">
            <div class="content-card" style="opacity: 1; transform: none;">
                <div class="text-center mb-4">
                    <div class="stat-icon mx-auto mb-3" style="width: 64px; height: 64px; border-radius: 50%;">
                        <i class="fa-solid {{ $isSuccess ? 'fa-check' : ($isPending ? 'fa-clock' : 'fa-circle-exclamation') }}"></i>
                    </div>
                    <h1 class="h3">{{ $isSuccess ? 'Booking confirmed' : ($isPending ? 'Payment processing' : 'Payment not completed') }}</h1>
                    <p class="text-muted">{{ $message }}</p>
                </div>

                <div class="price-calc">
                    <div class="calc-row"><span>Booking reference</span><strong>{{ $booking->booking_no }}</strong></div>
                    <div class="calc-row"><span>Trip</span><strong>{{ $itemName }}</strong></div>
                    <div class="calc-row"><span>Amount</span><strong>₹{{ number_format($payment->amount, 2) }}</strong></div>
                    <div class="calc-row total"><span>Payment status</span><strong>{{ ucfirst($payment->payment_status) }}</strong></div>
                </div>

                @if ($isSuccess)
                    <a class="btn-book-submit text-decoration-none" href="{{ route('home') }}">Continue exploring</a>
                @else
                    <a class="btn-book-submit text-decoration-none" href="{{ $detailsUrl }}">Return to trip</a>
                    <p class="small text-muted mt-3 mb-0">If money was deducted, keep your booking reference and contact support before making another payment.</p>
                @endif
            </div>
        </div>
    </section>
@endsection
