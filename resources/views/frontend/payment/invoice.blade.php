@extends('frontend.layouts.app')

@section('title', 'Booking invoice '.$booking->booking_no)

@push('styles')
<style>
    .invoice-wrap{max-width:900px;margin:auto}
    .invoice-sheet{border:1px solid #dfe7ed;border-radius:16px;background:#fff;box-shadow:0 12px 38px rgba(16,43,70,.08)}
    .invoice-header{display:flex;justify-content:space-between;gap:24px;padding:28px 32px;border-bottom:1px solid #e8edf1}
    .invoice-brand{display:flex;align-items:center;gap:14px}
    .invoice-logo{display:grid;width:54px;height:54px;place-items:center;border-radius:15px;color:#fff;background:#123d5c;font-size:1.35rem}
    .invoice-brand h1{margin:0;color:#123d5c;font-size:1.25rem}
    .invoice-brand p,.invoice-meta{margin:4px 0 0;color:#718092;font-size:.86rem}
    .invoice-body{padding:28px 32px}
    .invoice-section-title{margin:0 0 12px;color:#123d5c;font-size:.95rem;font-weight:800}
    .invoice-details{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:18px;margin-bottom:26px}
    .invoice-details p{margin:0 0 7px;color:#607489;font-size:.85rem}
    .invoice-details strong{color:#24384b}
    .invoice-total{margin-top:8px;border-top:2px solid #123d5c;padding-top:14px;font-size:1.1rem}
    .invoice-note{border:1px solid #e7edf2;border-radius:10px;padding:12px;color:#65798b;background:#f8fafc;font-size:.8rem}
    @media(max-width:575.98px){.invoice-header,.invoice-body{padding:20px}.invoice-header{flex-direction:column}.invoice-details{grid-template-columns:1fr}}
    @media print{body{background:#fff!important}.invoice-wrap{max-width:none}.invoice-sheet{border:0;box-shadow:none}.invoice-actions{display:none!important}.container{max-width:none!important;padding:0!important}}
</style>
@endpush

@section('content')
    @php
        $breakdown = $booking->gst_breakdown ?? [];
        $currency = $booking->currency ?: 'INR';
    @endphp
    <section class="container py-5">
        <div class="invoice-wrap">
            <div class="invoice-actions d-flex justify-content-end gap-2 mb-3">
                <a class="btn btn-outline-secondary" href="{{ route('home') }}">Back to site</a>
                <button class="btn btn-primary" type="button" onclick="window.print()">
                    <i class="fa-solid fa-print me-1"></i> Print / Save PDF
                </button>
            </div>

            <article class="invoice-sheet">
                <header class="invoice-header">
                    <div class="invoice-brand">
                        <div class="invoice-logo"><i class="fa-solid fa-mountain-sun"></i></div>
                        <div>
                            <h1>Uttarakhand Travels</h1>
                            <p>Booking payment receipt</p>
                        </div>
                    </div>
                    <div class="text-sm-end">
                        <h2 class="h4 mb-1">INVOICE</h2>
                        <div class="invoice-meta">Booking {{ $booking->booking_no }}</div>
                        <div class="invoice-meta">Issued {{ $booking->created_at?->format('d M Y') }}</div>
                    </div>
                </header>

                <div class="invoice-body">
                    <div class="invoice-details">
                        <section>
                            <h3 class="invoice-section-title">Customer</h3>
                            <p><strong>{{ $booking->contact_name ?: ($booking->user?->name ?? 'Guest') }}</strong></p>
                            <p>{{ $booking->contact_email ?: ($booking->user?->email ?? '—') }}</p>
                            <p>{{ $booking->contact_phone ?: '—' }}</p>
                        </section>
                        <section>
                            <h3 class="invoice-section-title">Booking</h3>
                            <p><strong>{{ $itemName }}</strong></p>
                            <p>{{ str()->headline($booking->item_type) }}</p>
                            @if ($booking->item_type === 'hotel')
                                <p>Rooms: {{ $booking->room_count }} · Guests: {{ $booking->guest }}</p>
                                <p>{{ $booking->check_in?->format('d M Y') }} – {{ $booking->check_out?->format('d M Y') }}</p>
                            @else
                                <p>Guests: {{ $booking->guest }}</p>
                                <p>Travel date: {{ $booking->travel_date?->format('d M Y') }}</p>
                            @endif
                        </section>
                    </div>

                    <h3 class="invoice-section-title">Price and GST</h3>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Description</th>
                                    <th class="text-end">Base amount</th>
                                    <th class="text-end">GST rate</th>
                                    <th class="text-end">GST</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($breakdown as $line)
                                    <tr>
                                        <td>
                                            {{ $line['description'] }}
                                            @if ($booking->item_type === 'hotel')
                                                <div class="small text-muted">{{ $line['quantity'] }} room(s) × {{ $line['nights'] }} night(s) × ₹{{ number_format((float) $line['unit_price'], 2) }}</div>
                                            @else
                                                <div class="small text-muted">{{ $line['quantity'] }} guest(s) × ₹{{ number_format((float) $line['unit_price'], 2) }}</div>
                                            @endif
                                        </td>
                                        <td class="text-end">{{ $currency }} {{ number_format((float) $line['base_amount'], 2) }}</td>
                                        <td class="text-end">{{ number_format((float) $line['gst_percentage'], 2) }}%</td>
                                        <td class="text-end">{{ $currency }} {{ number_format((float) $line['gst_amount'], 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Base amount</th>
                                    <th class="text-end">{{ $currency }} {{ number_format((float) $booking->taxable_amount, 2) }}</th>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-end">GST total</th>
                                    <th class="text-end">{{ $currency }} {{ number_format((float) $booking->gst_amount, 2) }}</th>
                                </tr>
                                <tr class="invoice-total">
                                    <th colspan="3" class="text-end">Final amount</th>
                                    <th class="text-end">{{ $currency }} {{ number_format((float) $payment->amount, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="invoice-details mt-4 mb-3">
                        <section>
                            <h3 class="invoice-section-title">Payment</h3>
                            <p>Status: <strong>{{ str()->headline($payment->payment_status) }}</strong></p>
                            <p>Gateway: {{ str()->headline($payment->gateway_name ?? 'Online') }}</p>
                            <p>Transaction ID: {{ $payment->transaction_id ?: $payment->gateway_payment_id ?: '—' }}</p>
                            <p>Paid on: {{ $payment->payment_date?->format('d M Y, h:i A') ?? '—' }}</p>
                        </section>
                        <section class="invoice-note">
                            GST is shown at the configured aggregate rate. No CGST/SGST or IGST split is stated because verified supplier registration and billing-place details are not recorded for this booking.
                        </section>
                    </div>
                    <p class="small text-muted mb-0">This receipt reflects the GST classification and rates saved when the booking was made.</p>
                </div>
            </article>
        </div>
    </section>
@endsection
