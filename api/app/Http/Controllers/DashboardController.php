<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;

class DashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware('role:Administrador');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $sales = Sale::betweenDates($request->from, $request->to)->get();
        $salesThisMonth = Sale::whereMonth('created_at', \Carbon\Carbon::now()->month)->get();

        $totalSales = $sales->count();

        $totalEarnings = 0;

        foreach ($sales as $key => $item) {
            $totalEarnings += $item->product()->get()->reduce(fn($a, $p) => $a + ($p->pivot->price * $p->pivot->quantity), 0);
        }

        $totalEarningsThisMonth = 0;

        foreach ($salesThisMonth as $key => $item) {
            $totalEarningsThisMonth += $item->product()->get()->reduce(fn($a, $p) => $a + ($p->pivot->price * $p->pivot->quantity), 0);
        }

        $arr = [
            'sales' => $totalSales,
            'earnings' => $totalEarnings,
            'earningsThisMonth' => $totalEarningsThisMonth,
        ];

        return response()->json(['data' => $arr], 200);

    }

}
