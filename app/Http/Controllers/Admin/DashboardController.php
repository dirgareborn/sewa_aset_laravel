<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\BookingService;
use App\Models\Unit;
use App\Models\ServiceBooking;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\Calculation\Web\Service as WebService;

class DashboardController extends Controller
{
    // public function dashboard(){
    //    return view('admin.dashboard');
    // }
    public function dashboard()
    {
        Session::put('page', 'dashboard');

        // === Statistik dasar ===
        $unitCount = Unit::count();
        $serviceCount = Service::count();
        $adminCount = Admin::count();
        $customerCount = User::count();

        // === PIE CHART ===
        $pie = Service::select('units.name', DB::raw('COUNT(*) AS total'))
            ->join('units', 'units.id', '=', 'services.unit_id')
            ->groupBy('units.name')
            ->get()
            ->map(fn ($row) => [
                'name' => $row->name,
                'y' => (int) $row->total,
            ])
            ->toArray();

        // === LINE CHART === (jumlah penyewaan per bulan)
        $lineRows = BookingService::select(
            DB::raw('MAX(bookings.created_at) AS created_at'),
            DB::raw('SUM(booking_services.qty) AS total')
        )
            ->join('bookings', 'bookings.id', '=', 'booking_services.booking_id')
            ->whereIn('bookings.status', ['approved', 'completed'])
            ->groupByRaw('YEAR(bookings.created_at), MONTH(bookings.created_at)')
            ->orderBy(DB::raw('MAX(bookings.created_at)'))
            ->get();

        $line = [
            'units' => $lineRows->pluck('created_at')->map(fn ($d) => date('M-Y', strtotime($d))),
            'data' => $lineRows->pluck('total')->map(fn ($v) => (int) $v),
        ];

        // === COLUMN CHART === (per kategori per tahun)
        $columnRows = BookingService::select(
            'units.name',
            DB::raw('YEAR(bookings.created_at) AS year'),
            DB::raw('SUM(booking_services.qty) AS total'),
            DB::raw('MAX(bookings.created_at) AS latest')
        )
            ->join('bookings', 'bookings.id', '=', 'booking_services.booking_id')
            ->whereIn('bookings.status', ['approved', 'completed'])
            ->join('services', 'services.id', '=', 'booking_services.service_id')
            ->join('units', 'units.id', '=', 'services.unit_id')
            ->groupBy('units.name', 'year')
            ->orderBy(DB::raw('MAX(bookings.created_at)'))
            ->get();

        $column = [
            'units' => [],
            'series' => [],
        ];

        foreach ($columnRows as $row) {
            $column['units'][$row->year] = $row->year;
            $column['series'][$row->category_name]['name'] = $row->category_name;
            $column['series'][$row->category_name]['data'][$row->year] = (int) $row->total;
        }

        foreach ($column['series'] as &$serie) {
            $serie['data'] = array_values($serie['data']);
        }

        $column['units'] = array_values($column['units']);
        $column['series'] = array_values($column['series']);

        return view('admin.dashboard', compact(
            'unitCount',
            'serviceCount',
            'adminCount',
            'customerCount',
            'pie',
            'line',
            'column'
        ));
    }
}
