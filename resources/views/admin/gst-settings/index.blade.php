@extends('layouts.admin')

@section('title', 'GST Settings')

@section('content')
    <div class="dashboard-container">
        <div class="page-header">
            <div>
                <h1 class="page-title">GST Settings</h1>
                <p class="page-subtitle">Configure hotel accommodation GST slabs used for new bookings.</p>
            </div>
            <div class="page-actions">
                <span class="badge-count">Package rates are configured per package</span>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-4 mb-4">
            <div class="col-md-5">
                <section class="content-card h-100">
                    <div class="card-header-custom">
                        <h2 class="h5 mb-0"><i class="fa-solid fa-chart-pie me-2"></i>GST collected</h2>
                    </div>
                    <div class="card-body-custom">
                        <p class="text-muted mb-1">From paid bookings</p>
                        <p class="display-6 fw-bold text-success mb-0">₹{{ number_format((float) $gstCollected, 2) }}</p>
                    </div>
                </section>
            </div>
            <div class="col-md-7">
                <section class="content-card h-100">
                    <div class="card-header-custom">
                        <h2 class="h5 mb-0"><i class="fa-solid fa-circle-info me-2"></i>Configuration notes</h2>
                    </div>
                    <div class="card-body-custom">
                        <p class="mb-2">Hotel slabs are selected using each room’s per-night tariff. GST is calculated for each selected room category and stay duration.</p>
                        <p class="text-muted small mb-0">Changes apply only to new bookings. Existing bookings keep their saved GST calculation. Confirm rates and eligibility conditions with your tax adviser before changing these values.</p>
                    </div>
                </section>
            </div>
        </div>

        <section class="content-card">
            <div class="card-header-custom">
                <h2 class="h5 mb-0"><i class="fa-solid fa-bed me-2"></i>Hotel tariff slabs</h2>
            </div>
            <div class="card-body-custom">
                <form method="POST" action="{{ route('admin.gst-settings.hotel-slabs.update') }}" class="modern-form">
                    @csrf
                    @method('PUT')
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Classification</th>
                                    <th>From (₹ / night)</th>
                                    <th>Up to (₹ / night)</th>
                                    <th>GST rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($slabs as $slab)
                                    <tr>
                                        <td class="fw-semibold">{{ $slab->name }}</td>
                                        <td>
                                            <input class="form-control" type="number" min="0" step="0.01"
                                                name="slabs[{{ $slab->id }}][min_tariff]"
                                                value="{{ old("slabs.{$slab->id}.min_tariff", $slab->min_tariff) }}" required>
                                        </td>
                                        <td>
                                            <input class="form-control" type="number" min="0" step="0.01"
                                                name="slabs[{{ $slab->id }}][max_tariff]"
                                                value="{{ old("slabs.{$slab->id}.max_tariff", $slab->max_tariff) }}"
                                                placeholder="No upper limit">
                                        </td>
                                        <td>
                                            <div class="input-group">
                                                <input class="form-control" type="number" min="0" max="100" step="0.01"
                                                    name="slabs[{{ $slab->id }}][gst_percentage]"
                                                    value="{{ old("slabs.{$slab->id}.gst_percentage", $slab->gst_percentage) }}" required>
                                                <span class="input-group-text">%</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <p class="form-hint">Threshold intervals must be contiguous to the nearest paise, starting at ₹0; leave only the final upper limit blank.</p>
                    <div class="d-flex justify-content-end mt-3">
                        <button class="btn btn-primary" type="submit"><i class="fa-solid fa-floppy-disk me-1"></i> Save hotel slabs</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection
