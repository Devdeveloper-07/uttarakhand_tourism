@extends('frontend.layouts.app')

@section('title', 'Secure payment')

@push('styles')
<style>
    .gst-checkout{max-width:760px;margin:auto}
    .gst-checkout-card{overflow:hidden;border:1px solid #e1e8ee;border-radius:20px;background:#fff;box-shadow:0 18px 48px rgba(16,43,70,.09)}
    .gst-checkout-head{padding:26px 28px;color:#fff;background:linear-gradient(115deg,#102b46,#176b8f)}
    .gst-checkout-head h1{color:#fff;font-size:clamp(1.35rem,3vw,1.8rem)}
    .gst-checkout-body{padding:28px}
    .gst-summary-line{display:flex;justify-content:space-between;gap:20px;padding:12px 0;border-bottom:1px solid #edf1f4}
    .gst-summary-line span{color:#5d7083}
    .gst-summary-line strong{text-align:right;color:#183a5c}
    .gst-tax-breakdown{margin-top:16px;border:1px solid #e7edf2;border-radius:14px;padding:16px;background:#f8fafc}
    .gst-line-detail{display:flex;justify-content:space-between;gap:14px;padding:7px 0;font-size:.88rem}
    .gst-rate-pill{display:inline-flex;align-items:center;border:1px solid #b9d8e9;border-radius:999px;padding:4px 10px;color:#17618d;background:#eef7fb;font-size:.78rem;font-weight:700}
    .gst-payable{display:flex;justify-content:space-between;align-items:center;gap:18px;margin-top:18px;border-radius:14px;padding:18px;color:#fff;background:#123d5c}
    .gst-payable span{color:#d8e9f4}
    .gst-payable strong{font-size:1.45rem}
    .gst-pay-button{width:100%;margin-top:20px;border:0;border-radius:12px;padding:15px 18px;color:#fff;background:#14765d;font-size:1rem;font-weight:800;transition:background .18s ease,transform .18s ease}
    .gst-pay-button:hover{transform:translateY(-1px);background:#105e4a}
    .gst-pay-button:focus-visible{outline:3px solid rgba(20,118,93,.25);outline-offset:3px}
    @media(max-width:575.98px){.gst-checkout-head,.gst-checkout-body{padding:20px}.gst-summary-line{gap:12px}.gst-payable{align-items:flex-start;flex-direction:column;gap:5px}}
</style>
@endpush

@section('content')
    @php
        $breakdown = $booking->gst_breakdown ?? [];
        $nights = $booking->check_in && $booking->check_out
            ? $booking->check_in->diffInDays($booking->check_out)
            : null;
    @endphp

    <section class="container py-5" style="min-height:65vh">
        <div class="gst-checkout">
            <div class="gst-checkout-card">
                <header class="gst-checkout-head">
                    <span class="badge rounded-pill bg-light text-primary-emphasis mb-2"><i class="fa-solid fa-lock me-1"></i> Secure checkout</span>
                    <h1 class="h3 mb-2">{{ $itemName }}</h1>
                    <p class="mb-0 text-white-50">Booking reference: <strong class="text-white">{{ $booking->booking_no }}</strong></p>
                </header>

                <div class="gst-checkout-body">
                    @if ($booking->item_type === 'hotel')
                        <div class="gst-summary-line">
                            <span>Hotel</span>
                            <strong>{{ $itemName }}</strong>
                        </div>
                        <div class="gst-summary-line">
                            <span>Room type and quantity</span>
                            <strong class="text-end">
                                @forelse ($breakdown as $line)
                                    {{ $line['room_type'] ?: $line['description'] }} · {{ $line['quantity'] }} {{ $line['quantity'] === 1 ? 'room' : 'rooms' }}<br>
                                @empty
                                    {{ $booking->room_count }} {{ $booking->room_count === 1 ? 'room' : 'rooms' }}
                                @endforelse
                            </strong>
                        </div>
                        <div class="gst-summary-line">
                            <span>Check-in / check-out</span>
                            <strong>{{ $booking->check_in?->format('d M Y') }} – {{ $booking->check_out?->format('d M Y') }}</strong>
                        </div>
                        <div class="gst-summary-line">
                            <span>Guests / nights</span>
                            <strong>{{ $booking->guest }} guests · {{ $nights }} {{ $nights === 1 ? 'night' : 'nights' }}</strong>
                        </div>
                        @foreach ($breakdown as $line)
                            <div class="gst-summary-line">
                                <span>{{ $line['description'] }} · {{ $line['quantity'] }} × {{ $line['nights'] }} nights × ₹{{ number_format((float) $line['unit_price'], 2) }}</span>
                                <strong>₹{{ number_format((float) $line['base_amount'], 2) }}</strong>
                            </div>
                        @endforeach
                    @else
                        <div class="gst-summary-line">
                            <span>Package</span>
                            <strong>{{ $itemName }}</strong>
                        </div>
                        <div class="gst-summary-line">
                            <span>Travel date</span>
                            <strong>{{ $booking->travel_date?->format('d M Y') }}</strong>
                        </div>
                        <div class="gst-summary-line">
                            <span>Number of guests</span>
                            <strong>{{ $booking->guest }}</strong>
                        </div>
                        <div class="gst-summary-line">
                            <span>Package price per guest</span>
                            <strong>₹{{ number_format((float) data_get($breakdown, '0.unit_price', 0), 2) }}</strong>
                        </div>
                    @endif

                    <div class="gst-summary-line">
                        <span>Base amount</span>
                        <strong>₹{{ number_format((float) $payment->base_amount, 2) }}</strong>
                    </div>

                    <div class="gst-tax-breakdown">
                        <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                            <strong>GST</strong>
                            @if (count($breakdown) === 1)
                                <span class="gst-rate-pill">{{ number_format((float) $breakdown[0]['gst_percentage'], 2) }}%</span>
                            @else
                                <span class="gst-rate-pill">Tariff-based rates</span>
                            @endif
                        </div>
                        @foreach ($breakdown as $line)
                            <div class="gst-line-detail">
                                <span>{{ $line['description'] }} · {{ number_format((float) $line['gst_percentage'], 2) }}%</span>
                                <strong>₹{{ number_format((float) $line['gst_amount'], 2) }}</strong>
                            </div>
                        @endforeach
                        <div class="gst-line-detail border-top mt-2 pt-3">
                            <strong>Total GST</strong>
                            <strong>₹{{ number_format((float) $payment->gst_amount, 2) }}</strong>
                        </div>
                    </div>

                    <div class="gst-payable">
                        <span>Final payable amount (GST included)</span>
                        <strong>₹{{ number_format((float) $payment->amount, 2) }}</strong>
                    </div>

                    <p id="payment-message" class="small text-muted mt-3 mb-0" role="status">
                        Review the complete price above, then continue to secure Razorpay checkout.
                    </p>

                    <button type="button" class="gst-pay-button" id="pay-now">
                        <i class="fa-solid fa-lock me-2"></i> Pay ₹{{ number_format((float) $payment->amount, 2) }}
                    </button>
                    <a class="d-inline-block mt-3" href="{{ $booking->item_type === 'hotel' ? route('hotel.details', $booking->item_id) : route('package.details', $booking->item_id) }}">
                        Cancel and return
                    </a>

                    <form id="verify-payment-form" method="POST" action="{{ route('payments.verify', $payment) }}" hidden>
                        @csrf
                        <input type="hidden" name="razorpay_payment_id">
                        <input type="hidden" name="razorpay_order_id">
                        <input type="hidden" name="razorpay_signature">
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        const paymentOptions = {
            key: @json($key),
            amount: @json($amountPaise),
            currency: 'INR',
            name: 'Uttarakhand Tourism',
            description: @json($itemName),
            order_id: @json($payment->gateway_order_id),
            prefill: {
                name: @json($contact['name']),
                email: @json($contact['email']),
                contact: @json($contact['phone'])
            },
            theme: { color: '#176b55' },
            handler: function (response) {
                const form = document.getElementById('verify-payment-form');
                form.elements.razorpay_payment_id.value = response.razorpay_payment_id;
                form.elements.razorpay_order_id.value = response.razorpay_order_id;
                form.elements.razorpay_signature.value = response.razorpay_signature;
                form.submit();
            },
            modal: {
                ondismiss: function () {
                    document.getElementById('payment-message').textContent =
                        'Checkout was closed. Your booking is not confirmed until payment succeeds.';
                }
            }
        };

        const razorpayCheckout = new Razorpay(paymentOptions);
        razorpayCheckout.on('payment.failed', function (response) {
            const description = response.error && response.error.description;
            document.getElementById('payment-message').textContent =
                description || 'Payment was not completed. You can try again using the same secure order.';
        });

        document.getElementById('pay-now').addEventListener('click', function () {
            razorpayCheckout.open();
        });
    </script>
@endsection
