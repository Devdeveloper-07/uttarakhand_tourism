@extends('frontend.layouts.app')

@section('title', 'Secure payment')

@section('content')
    <section class="container py-5" style="min-height: 65vh;">
        <div class="mx-auto" style="max-width: 620px;">
            <div class="content-card animate-in" style="opacity: 1; transform: none;">
                <span class="section-label"><i class="fa-solid fa-lock"></i> Secure checkout</span>
                <h1 class="h3 mt-3">{{ $itemName }}</h1>
                <p class="text-muted">Booking reference: <strong>{{ $booking->booking_no }}</strong></p>

                <div class="price-calc">
                    <div class="calc-row">
                        <span>{{ $booking->item_type === 'hotel' ? 'Stay total' : 'Package total' }}</span>
                        <strong>₹{{ number_format($payment->amount, 2) }}</strong>
                    </div>
                    <div class="calc-row total">
                        <span>Amount payable</span>
                        <strong>₹{{ number_format($payment->amount, 2) }}</strong>
                    </div>
                </div>

                <p id="payment-message" class="text-muted" role="status">
                    Continue to Razorpay to pay securely by UPI, card, net banking, or wallet.
                </p>

                <button type="button" class="btn-book-submit" id="pay-now">
                    <i class="fa-solid fa-lock"></i> Pay ₹{{ number_format($payment->amount, 2) }}
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
                description || 'Payment was not completed. Please try again.';
        });

        document.getElementById('pay-now').addEventListener('click', function () {
            razorpayCheckout.open();
        });

        razorpayCheckout.open();
    </script>
@endsection
