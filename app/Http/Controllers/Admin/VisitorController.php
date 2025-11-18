<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Visitor;
use Carbon\Carbon;

class VisitorController extends Controller
{
    public function index()
    {
        $totalVisitors = Visitor::count();
        $todayVisitors = Visitor::whereDate('created_at', now())->count();

        // Data chart 7 hari terakhir
        $visitorsChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $count = Visitor::whereDate('created_at', $date)->count();
            $visitorsChart[] = ['date' => $date, 'count' => $count];
        }

        // Visitor per kota
        $visitorsByCity = Visitor::select('city', Visitor::raw('count(*) as total'))
            ->groupBy('city')
            ->orderByDesc('total')
            ->get();

        return view('admin.visitors.index', compact('totalVisitors', 'todayVisitors', 'visitorsChart', 'visitorsByCity'));
    }
}
