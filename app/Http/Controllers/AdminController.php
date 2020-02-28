<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;
use App\Models\User;
use App\Models\Store;
use App\Models\Purchase;
use Session;
use Image;
use Carbon\Carbon;
use App\Charts\SalesChart;

class AdminController extends Controller
{
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
	public function getAdminDashboard(){
        
        $monthly = Purchase::orderBy('created_at', 'ASC')->get()->groupBy(function($d) {
            return Carbon::parse($d->created_at)->format('n');
        });

        for($ms = 1; $ms <= 12; $ms++){

            if(array_key_exists($ms, $monthly->toArray())){

                $monthlySales[] = $monthly[$ms]->sum('grand_total');

            }else{

                $monthlySales[] = 0;

            }
        }

        $month = ['January', 
            'February', 
            'March',
            'April', 
            'May',
            'June', 
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        ];

        $yearly = Purchase::orderBy('created_at', 'ASC')->get()->groupBy(function($d) {
            return Carbon::parse($d->created_at)->format('Y');
        });

        for($ys = 2019; $ys <= date('Y'); $ys++){

            if(array_key_exists($ys, $yearly->toArray())){

                $yearlySales[] = $yearly[$ys]->sum('grand_total');

            }else{

                $yearlySales[] = 0;

            }
            
            
        }

        for($y = 2019; $y <= date('Y'); $y++){

            $year[] = $y;
        }

        $Monthlychart = new SalesChart;
        $Monthlychart->labels($month);
        $Monthlychart->dataset('Monthly Sales', 'line', $monthlySales)->backgroundcolor('transparent')->color('#6777ef');

        $Yearlychart = new SalesChart;
        $Yearlychart->labels($year);
        $Yearlychart->dataset('Yearly Sales', 'line', $yearlySales)->backgroundcolor('transparent')->color('#6777ef');

		$user = Auth::user();

        $purchases = Purchase::orderBy('created_at', 'DESC')->take(5)->get();

		return view('admin.dashboard')
				->with('user', $user)
                ->with('purchases', $purchases)
                ->with('Monthlychart', $Monthlychart)
                ->with('Yearlychart', $Yearlychart);
	
	}

    public function getAdminDashboardData(Request $request) {

        if ($request->wantsJson()) {

            $summary = adminDashboardData();

            return response()->json([
                'summary' => $summary,
            ],200);

        } else {

            return response()->json(['result' => false, 'message' => 'Something went wrong. Please try again!'], 422);
        }
    }
}