<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;
use App\Models\User;
use App\Models\Store;
use App\Models\Purchase;
use Session;
use Carbon\Carbon;
use App\Charts\SalesChart;

class SalesController extends Controller
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

    public function getDailySales(){

        $user = Auth::user();

        $daily = Purchase::whereDate('created_at', '>=', Carbon::now()->today())
                            ->whereDate('created_at', '<=', Carbon::now()->today())
                            ->orderBy('created_at', 'ASC')
                            ->get()
                            ->groupBy(function($d) {
            return Carbon::parse($d->created_at)->format('G');
        });

        for($ds = 0; $ds <= 23; $ds++){

            if(array_key_exists($ds, $daily->toArray())){

                $dailySales[] = $daily[$ds]->sum('grand_total');

            }else{

                $dailySales[] = 0;
            }

        }

        for($hour = 0; $hour <= 23; $hour++){

            $time[] = $hour;
        }

        $dailyChart = new SalesChart;
        $dailyChart->labels($time);
        $dailyChart->dataset('Daily Sales', 'line', $dailySales)->backgroundcolor('transparent')->color('#6777ef');
        
        return view('admin.sales.daily')
                ->with('user', $user)
                ->with('daily', $daily)
                ->with('dailyChart', $dailyChart);

    }
    public function getDailySalesData(Request $request){

        if ($request->wantsJson()) {

            $user = Auth::user();
    
            $purchases = Purchase::whereDate('created_at', '>=', Carbon::now()->today())
                                ->whereDate('created_at', '<=', Carbon::now()->today())
                                ->orderBy('created_at', 'ASC');
            
            if($purchases) {

                return Datatables::of($purchases)
                ->addColumn('invoice', function ($purchases) {
                    return $purchases->invoice_id;
                })
                ->addColumn('cashier', function ($purchases) {
                    return $purchases->user->username;
                })
                ->addColumn('store', function ($purchases) {
                    return $purchases->store->code;
                })
                ->addColumn('purchases', function ($purchases) {
                    return '<a href="" ng-click="form.showPurchasess('.$purchases->id.')">Show Items</a>';
                })
                ->addColumn('total', function ($purchases) {
                    return '&#8369; '.number_format($purchases->grand_total, 2);
                })
                ->addColumn('date', function ($purchases) {
                    return date('F j, Y', strtotime($purchases->created_at)) . ' | ' . Carbon::parse($purchases->created_at)->diffForHumans();
                })
                ->addIndexColumn()
                ->rawColumns(['invoice','cashier','store','purchases','total','date'])
                ->make(true);

            }else{

                return response()->json(array('result' => 'danger', 'message' => 'Something went wrong. Please try again.'),422);
            }

        } else{

            return response()->json(array('result' => 'danger', 'message' => 'Something went wrong. Please try again!'),422);
        }
    }

    public function getWeeklySales(){

        $user = Auth::user();

        $weekly = Purchase::whereDate('created_at', '>=', Carbon::now()->startOfWeek())
                            ->whereDate('created_at', '<=', Carbon::now()->endOfWeek())
                            ->orderBy('created_at', 'ASC')
                            ->get()
                            ->groupBy(function($d) {
                return Carbon::parse($d->created_at)->format('N');
        });


        for($ws = 1; $ws <= 7; $ws++){

            if(array_key_exists($ws, $weekly->toArray())){

                $weeklySales[] = $weekly[$ws]->sum('grand_total');

            }else{

                $weeklySales[] = 0;
            }

        }

        $week = [
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday',
            'Sunday'
        ];

        $weeklyChart = new SalesChart;
        $weeklyChart->labels($week);
        $weeklyChart->dataset('Weekly Sales', 'line', $weeklySales)->backgroundcolor('transparent')->color('#6777ef');
        
        return view('admin.sales.weekly')
                ->with('user', $user)
                ->with('weekly', $weekly)
                ->with('weeklyChart', $weeklyChart);

    }

    public function getWeeklySalesData(Request $request){

        if ($request->wantsJson()) {

            $user = Auth::user();
    
            $purchases = Purchase::whereDate('created_at', '>=', Carbon::now()->startOfWeek())
                            ->whereDate('created_at', '<=', Carbon::now()->endOfWeek())
                            ->orderBy('created_at', 'ASC');
            
            if($purchases) {

                return Datatables::of($purchases)
                ->addColumn('invoice', function ($purchases) {
                    return $purchases->invoice_id;
                })
                ->addColumn('cashier', function ($purchases) {
                    return $purchases->user->username;
                })
                ->addColumn('store', function ($purchases) {
                    return $purchases->store->code;
                })
                ->addColumn('purchases', function ($purchases) {
                    return '<a href="" ng-click="form.showPurchasess('.$purchases->id.')">Show Items</a>';
                })
                ->addColumn('total', function ($purchases) {
                    return '&#8369; '.number_format($purchases->grand_total, 2);
                })
                ->addColumn('date', function ($purchases) {
                    return date('F j, Y', strtotime($purchases->created_at)) . ' | ' . Carbon::parse($purchases->created_at)->diffForHumans();
                })
                ->addIndexColumn()
                ->rawColumns(['invoice','cashier','store','purchases','total','date'])
                ->make(true);

            }else{

                return response()->json(array('result' => 'danger', 'message' => 'Something went wrong. Please try again.'),422);
            }

        } else{

            return response()->json(array('result' => 'danger', 'message' => 'Something went wrong. Please try again!'),422);
        }
    }

    public function getMonthlySales(){
            
        $user = Auth::user();

        $monthly =  Purchase::whereDate('created_at', '>=', Carbon::now()->startOfMonth())
                            ->whereDate('created_at', '<=', Carbon::now()->endOfMonth())
                            ->orderBy('created_at', 'ASC')
                            ->get()
                            ->groupBy(function($d) {
                    return Carbon::parse($d->created_at)->format('j');
        });

        for($ms = 1; $ms <= Carbon::now()->endOfMonth()->format('j'); $ms++){

            if(array_key_exists($ms, $monthly->toArray())){

                $monthlySales[] = $monthly[$ms]->sum('grand_total');

            }else{

                $monthlySales[] = 0;

            }
        }

        for($md = 1; $md <= Carbon::now()->endOfMonth()->format('j'); $md++){

            $days[] = 'Day '.$md;
        }

        $monthlyChart = new SalesChart;
        $monthlyChart->labels($days);
        $monthlyChart->dataset('Monthly Sales', 'line', $monthlySales)->backgroundcolor('transparent')->color('#6777ef');
        
        return view('admin.sales.monthly')
                ->with('user', $user)
                ->with('monthly', $monthly)
                ->with('monthlyChart', $monthlyChart);
    }

    public function getMonthlySalesData(Request $request){

        if ($request->wantsJson()) {

            $user = Auth::user();
    
            $purchases = Purchase::whereDate('created_at', '>=', Carbon::now()->startOfMonth())
                            ->whereDate('created_at', '<=', Carbon::now()->endOfMonth())
                            ->orderBy('created_at', 'ASC');
            
            if($purchases) {

                return Datatables::of($purchases)
                ->addColumn('invoice', function ($purchases) {
                    return $purchases->invoice_id;
                })
                ->addColumn('cashier', function ($purchases) {
                    return $purchases->user->username;
                })
                ->addColumn('store', function ($purchases) {
                    return $purchases->store->code;
                })
                ->addColumn('purchases', function ($purchases) {
                    return '<a href="" ng-click="form.showPurchasess('.$purchases->id.')">Show Items</a>';
                })
                ->addColumn('total', function ($purchases) {
                    return '&#8369; '.number_format($purchases->grand_total, 2);
                })
                ->addColumn('date', function ($purchases) {
                    return date('F j, Y', strtotime($purchases->created_at)) . ' | ' . Carbon::parse($purchases->created_at)->diffForHumans();
                })
                ->addIndexColumn()
                ->rawColumns(['invoice','cashier','store','purchases','total','date'])
                ->make(true);

            }else{

                return response()->json(array('result' => 'danger', 'message' => 'Something went wrong. Please try again.'),422);
            }

        } else{

            return response()->json(array('result' => 'danger', 'message' => 'Something went wrong. Please try again!'),422);
        }
    }

    public function getYearlySales(){
        
        $user = Auth::user();

        $yearly = Purchase::orderBy('created_at', 'ASC')
                            ->get()
                            ->groupBy(function($d) {
                    return Carbon::parse($d->created_at)->format('n');
        });

        for($ys = 1; $ys <= 12; $ys++){

            if(array_key_exists($ys, $yearly->toArray())){

                $yearlySales[] = $yearly[$ys]->sum('grand_total');

            }else{

                $yearlySales[] = 0;

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

        $yearlyChart = new SalesChart;
        $yearlyChart->labels($month);
        $yearlyChart->dataset('Yearly Sales', 'line', $yearlySales)->backgroundcolor('transparent')->color('#6777ef');
        
        return view('admin.sales.yearly')
                ->with('user', $user)
                ->with('yearly', $yearly)
                ->with('yearlyChart', $yearlyChart);

    }
    public function getYearlySalesData(Request $request){

        if ($request->wantsJson()) {

            $user = Auth::user();
    
            $purchases = Purchase::whereDate('created_at', '>=', Carbon::now()->startOfYear())
                            ->whereDate('created_at', '<=', Carbon::now()->endOfYear())
                            ->orderBy('created_at', 'ASC');
            
            if($purchases) {

                return Datatables::of($purchases)
                ->addColumn('invoice', function ($purchases) {
                    return $purchases->invoice_id;
                })
                ->addColumn('cashier', function ($purchases) {
                    return $purchases->user->username;
                })
                ->addColumn('store', function ($purchases) {
                    return $purchases->store->code;
                })
                ->addColumn('purchases', function ($purchases) {
                    return '<a href="" ng-click="form.showPurchasess('.$purchases->id.')">Show Items</a>';
                })
                ->addColumn('total', function ($purchases) {
                    return '&#8369; '.number_format($purchases->grand_total, 2);
                })
                ->addColumn('date', function ($purchases) {
                    return date('F j, Y', strtotime($purchases->created_at)) . ' | ' . Carbon::parse($purchases->created_at)->diffForHumans();
                })
                ->addIndexColumn()
                ->rawColumns(['invoice','cashier','store','purchases','total','date'])
                ->make(true);

            }else{

                return response()->json(array('result' => 'danger', 'message' => 'Something went wrong. Please try again.'),422);
            }

        } else{

            return response()->json(array('result' => 'danger', 'message' => 'Something went wrong. Please try again!'),422);
        }
    }

}