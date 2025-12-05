<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingService;
use App\Models\Payment;
use App\Models\Service;
use App\Models\ServicePrice;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Exports\BookingsExport;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    // Daftar booking
    public function index(Request $request)
    {
        $query = Booking::with(['bookingservices.service', 'user'])
            ->orderByDesc('id');

        if ($request->filled('status')) {
            $query->where('booking_status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'LIKE', "%{$search}%")
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'LIKE', "%{$search}%"));
            });
        }

        $bookings = $query->paginate(10)->withQueryString();

        $users = User::orderBy('name')->get(['id', 'name','customer_type']);
        $services = Service::orderBy('name')->get(['id', 'name','base_price']);

        return view('admin.bookings.index', compact('bookings', 'users', 'services'));
    }

    // Lihat detail booking
    public function show($id)
    {
        $booking = Booking::with(['bookingservices.service', 'user', 'payments'])->findOrFail($id);

        return view('admin.bookings.show', compact('booking'));
    }

    // Form edit pembayaran/verifikasi
    public function edit($id)
    {
        $booking = Booking::with(['bookingservices.service', 'user', 'payments'])->findOrFail($id);
        $statuses = ['waiting', 'approved', 'completed', 'rejected', 'cancelled'];

        return view('admin.bookings.edit', compact('booking', 'statuses'));
    }

    // public function edit(Booking $booking)
    // {
    //     $users = User::all();
    //     $services = Service::all();
    //     $booking->load('services','payments','user');
    //     return view('admin.bookings.edit', compact('booking','users','services'));
    // }

    // update booking + services + payment adjustments
    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'booking_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'coupon_code' => 'nullable|string',
            'coupon_amount' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,paid,cancelled,expired',
            'is_internal' => 'required|boolean',
            'snap_token' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $booking->update([
                'user_id' => $request->user_id,
                'booking_date' => Carbon::parse($request->booking_date),
                'amount' => $request->amount,
                'coupon_code' => $request->coupon_code,
                'coupon_amount' => $request->coupon_amount ?? 0,
                'status' => $request->status,
                'snap_token' => $request->snap_token,
                'is_internal' => (bool)$request->is_internal,
            ]);

            // Optionally sync booking services: not overwritten automatically in this simple code.
            // If admin posts services[] you can delete existing and recreate:
            if ($request->filled('services')) {
                $booking->services()->delete();
                foreach ($request->services as $s) {
                    BookingService::create([
                        'user_id' => $request->user_id,
                        'booking_id' => $booking->id,
                        'customer_type' => $s['customer_type'] ?? null,
                        'service_id' => $s['service_id'] ?? null,
                        'name' => $s['name'] ?? null,
                        'price' => $s['price'] ?? 0,
                        'start_date' => $s['start_date'] ?? null,
                        'end_date' => $s['end_date'] ?? null,
                        'qty' => $s['qty'] ?? 1,
                    ]);
                }
            }

            // If status moved to paid and no payment exists, create payment
            if ($booking->status === 'paid' && $booking->payments()->where('status','paid')->count() === 0) {
                Payment::create([
                    'booking_id' => $booking->id,
                    'amount' => $booking->amount,
                    'method' => $request->input('payment.method','transfer'),
                    'status' => 'paid',
                    'verified_by_admin_id' => auth('admin')->id() ?? null,
                    'verified_at' => Carbon::now(),
                    'paid_at' => Carbon::now(),
                ]);
            }

            DB::commit();
            return redirect()->route('admin.bookings.index')->with('success','Booking updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // Update pembayaran/verifikasi
    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $request->validate([
            'payment_status' => 'required|in:unpaid,pending,paid,failed,refunded',
            'payment_verifier_note' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            // Ambil payment terbaru
            $payment = $booking->payments()->latest()->first();

            if (! $payment) {
                $payment = new Payment(['booking_id' => $booking->id]);
            }

            $payment->update([
                'payment_status' => $request->payment_status,
                'verified_by_admin_id' => auth('admin')->id(),
                'payment_verified_at' => Carbon::now(),
                'payment_verifier_note' => $request->payment_verifier_note,
            ]);

            // Map booking_status otomatis
            $statusMap = [
                'unpaid' => 'waiting',
                'pending' => 'waiting',
                'paid' => 'approved',
                'failed' => 'rejected',
                'refunded' => 'cancelled',
            ];

            $booking->booking_status = $statusMap[$request->payment_status] ?? $booking->booking_status;
            $booking->save();

            DB::commit();

            return redirect()->route('admin.bookings.index')->with('success', 'Booking diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // Cek ketersediaan service
    public function checkAvailability(Request $request)
    {
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $service_id = $request->service_id;

        if (! $start_date || ! $end_date || ! $service_id) {
            return response()->json([
                'status' => false,
                'message' => 'Lengkapi tanggal dan layanan terlebih dahulu',
            ]);
        }

        $exists = BookingService::where('service_id', $service_id)
            ->where(function ($q) use ($start_date, $end_date) {
                $q->whereBetween('start_date', [$start_date, $end_date])
                    ->orWhereBetween('end_date', [$start_date, $end_date])
                    ->orWhereRaw('? BETWEEN start_date AND end_date', [$start_date])
                    ->orWhereRaw('? BETWEEN start_date AND end_date', [$end_date]);
            })->exists();

        return response()->json([
            'status' => ! $exists,
            'message' => $exists ? 'Jadwal tidak tersedia ❌' : 'Jadwal tersedia ✅',
        ]);
    }

    // Ambil harga service
    public function getPrice(Request $request)
    {
        $request->validate([
            'service_id' => 'required',
            'customer_type' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $qty = convert_date_to_qty($request->start_date, $request->end_date);

        $unitPrice = ServicePrice::where('service_id', $request->service_id)
            ->where('customer_type', $request->customer_type)
            ->value('price');

        return response()->json([
            'status' => true,
            'price' => $unitPrice,
            'qty' => $qty,
            'total' => $unitPrice * $qty,
        ]);
    }

    // Buat booking baru via admin/internal
    public function store(Request $request)
    {
        // dd($request->all());
        // $request->validate([
        //     'user_id' => 'nullable|exists:users,id',
        //     'booking_date' => 'required|date',
        //     'grand_total' => 'required|numeric|min:0',
        //     'coupon_code' => 'nullable|string',
        //     'coupon_amount' => 'nullable|numeric|min:0',
        //     // 'status' => 'nullable|in:pending,paid,cancelled,expired',
        //     'snap_token' => 'nullable|string',
        //     'is_internal' => 'required|boolean',
        //     // optional arrays for services (if admin supplies)
        //     'services' => 'nullable|array',
        //     'services.*.service_id' => 'nullable|exists:services,id',
        //     'services.*.name' => 'nullable|string',
        //     'services.*.price' => 'nullable|numeric|min:0',
        //     'services.*.start_date' => 'nullable|date',
        //     'services.*.end_date' => 'nullable|date|after_or_equal:services.*.start_date',
        //     'services.*.qty' => 'nullable|integer|min:1',
        //     'payment.method' => 'nullable|string',
        // ]);
        $request->validate([
            'service_id' => 'required',
            'customer_type' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
        DB::beginTransaction();
        try {
             // Create user baru jika perlu
            if ($request->filled('new_customer_name')) {
                $name = $request->new_customer_name;
                $customerType = $request->customer_type;
                $email = Str::slug($name).'@gmail.com';
                $password = bcrypt('123456');

                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => $password,
                    'customer_type' => $customerType,
                    'status' => 1,
                ]);

                $userId = $user->id;
            } else {
                $userId = $request->user_id;
                $customerType = $request->customer_type;
            }
            
            $qty = convert_date_to_qty($request->start_date, $request->end_date);
            $s = Service::findOrFail($request->service_id);
            $booking = Booking::create([
                'invoice_number' => Booking::generateInvoiceNumber(),
                'user_id' => $userId,
                'coupon_code' => $request->coupon_code ?? null,
                'coupon_amount' => $request->coupon_amount ?? 0,
                'status' => $request->status ?? 'approved',
                'email_sent_at' => null,
                'amount' => $request->grand_total,
                'booking_date' => Carbon::parse($request->booking_date),
                'snap_token' => $request->snap_token ?? null,
                'is_internal' => (bool)$request->is_internal,
            ]);

            BookingService::create([
                        'user_id' => $userId,
                        'booking_id' => $booking->id,                        
                        'customer_type' => $customerType,
                        'service_id' => $request->service_id,
                        'name' => $s->name ?? null,
                        'price' => $s->base_price ?? 0,
                        'start_date' => $s->start_date,
                        'end_date' => $s->end_date,
                        'qty' => $qty ?? 1,
                    ]);

            // if internal booking and status=approved create payment record automatically
            if ($booking->is_internal && $booking->status === 'approved') {
                Payment::create([
                    'booking_id' => $booking->id,
                    'amount' => $booking->amount,
                    'method' => $request->input('payment.method', 'transfer'),
                    'status' => 'paid',
                    'verified_by_admin_id' => auth('admin')->id() ?? null,
                    'verified_at' => Carbon::now(),
                    'paid_at' => Carbon::now(),
                ]);
            }

            DB::commit();

            return redirect()->route('admin.bookings.index')->with('success','Booking berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // Export Excel
    public function export()
    {
        $fileName = 'bookings_'.now()->format('Ymd_His').'.xlsx';

        return Excel::download(new BookingsExport, $fileName);
    }

    // Export PDF
    public function exportPdf()
    {
        $bookings = Booking::with(['bookingservices.service', 'user', 'payments'])->orderByDesc('id')->get();
        $pdf = PDF::loadView('admin.bookings.export_pdf', compact('bookings'))->setPaper('a4', 'landscape');

        return $pdf->download('bookings.pdf');
    }
}
