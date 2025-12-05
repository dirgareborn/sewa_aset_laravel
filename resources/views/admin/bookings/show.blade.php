@extends('admin.layout.app')
@section('title', 'Detail Booking')
@section('content')
    <section class="content">
        <div class="container-fluid py-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4>Detail Booking: {{ $booking->invoice_number }}</h4>
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-3">Invoice</dt>
                        <dd class="col-sm-9">{{ $booking->invoice_number }}</dd>
                        <dt class="col-sm-3">User</dt>
                        <dd class="col-sm-9">{{ $booking->user->name ?? '-' }} ({{ $booking->user->email ?? '-' }})</dd>
                        <dt class="col-sm-3">Booking Date</dt>
                        <dd class="col-sm-9">{{ $booking->booking_date?->format('d M Y H:i') }}</dd>
                        <dt class="col-sm-3">Amount</dt>
                        <dd class="col-sm-9">Rp {{ number_format($booking->amount, 0, ',', '.') }}</dd>
                        <dt class="col-sm-3">Coupon</dt>
                        <dd class="col-sm-9">{{ $booking->coupon_code ?? '-' }} <small class="text-success">- Rp
                                {{ number_format($booking->coupon_amount ?? 0, 0, ',', '.') }}</small></dd>
                        <dt class="col-sm-3">Status</dt>
                        <dd class="col-sm-9">{{ ucfirst($booking->status) }}</dd>
                        <dt class="col-sm-3">Email Sent</dt>
                        <dd class="col-sm-9">{{ $booking->email_sent_at?->format('d M Y H:i') ?? '-' }}</dd>
                        <dt class="col-sm-3">Snap Token</dt>
                        <dd class="col-sm-9">{{ $booking->snap_token ?? '-' }}</dd>
                        <dt class="col-sm-3">Internal</dt>
                        <dd class="col-sm-9">{{ $booking->is_internal ? 'Yes' : 'No' }}</dd>
                    </dl>

                    <hr>
                    <h5>Services</h5>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Start</th>
                                <th>End</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($booking->services as $s)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $s->name }}</td>
                                    <td>Rp {{ number_format($s->price, 0, ',', '.') }}</td>
                                    <td>{{ $s->qty }}</td>
                                    <td>{{ $s->start_date?->format('d M Y') }}</td>
                                    <td>{{ $s->end_date?->format('d M Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <hr>
                    <h5>Payments</h5>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Verified By</th>
                                <th>Paid At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($booking->payments as $p)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>Rp {{ number_format($p->amount, 0, ',', '.') }}</td>
                                    <td>{{ $p->method }}</td>
                                    <td>{{ ucfirst($p->status) }}</td>
                                    <td>{{ $p->verifier->name ?? '-' }}</td>
                                    <td>{{ $p->paid_at?->format('d M Y H:i') ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </section>
@endsection
