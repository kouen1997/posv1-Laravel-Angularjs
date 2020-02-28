<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;
use App\Http\Requests\BrandRequest;
use App\Models\User;
use App\Models\Coupon;
use Carbon\Carbon;


class CouponController extends Controller
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
	public function getCoupon()
    {
        $user = Auth::user();

        return view('admin.product.coupon', compact('user'));
    }

    public function getCouponData(Request $request)
    {
        if ($request->wantsJson()) {

            $user = Auth::user();
    
            $coupon = Coupon::orderBy('created_at', 'DESC');
            
            if($coupon) {

                return Datatables::of($coupon)
                ->editColumn('code', function ($coupon) {
                    return $coupon->code;
                })
                ->editColumn('name', function ($coupon) {
                    return $coupon->name;
                })
                ->addColumn('minimum', function ($coupon) {
                    return '&#8369; '.number_format($coupon->minimum);
                })
                ->addColumn('discount', function ($coupon) {
                    return '&#8369; '.number_format($coupon->discount);
                })
                ->addColumn('mechanics', function ($coupon) {
                    return $coupon->mechanics;
                })
                ->addColumn('promo_start', function ($coupon) {
                    return date('M d Y', strtotime($coupon->promo_start));
                })
                ->addColumn('promo_end', function ($coupon) {
                    return date('M d Y', strtotime($coupon->promo_end));
                })
                ->addColumn('date', function ($coupon) {
                    return date('F j, Y', strtotime($coupon->created_at)) . ' | ' . Carbon::parse($coupon->created_at)->diffForHumans();
                })
                ->addColumn('action', function ($coupon) {
                    return '<a href="" ng-click="form.editCoupon('.$coupon->id.')" class="btn btn-info btn-sm"><i class="fa fa-edit"></i> Edit</a>

                            <a href="" ng-click="form.deleteCoupon('.$coupon->id.')" class="btn btn-danger btn-sm"><i class="fa fa-times-circle"></i> Delete</a>';
                })
                ->addIndexColumn()
                ->rawColumns(['code','name','minimum','discount','mechanics','promo_start','promo_end','action'])
                ->make(true);

            }else{

                return response()->json(array('result' => 'danger', 'message' => 'Something went wrong. Please try again.'),422);
            }

        } else{

            return response()->json(array('result' => 'danger', 'message' => 'Something went wrong. Please try again!'),422);
        }
    }

    public function postAddCoupon(Request $request)
    {
        try{

            if($request->wantsJson()) { 

                $user = Auth::user();

                $couponExists = Coupon::where('code', $request->coupon_code)->first();

                if($couponExists){

                    return response()->json(['status' => 'danger', 'message' => 'Coupon Exists'],200); 

                }

                $coupon = new Coupon;
                $coupon->code = $request->coupon_code;
                $coupon->name = $request->coupon_name;
                $coupon->minimum = $request->min_spend;
                $coupon->discount = $request->discount;
                $coupon->mechanics = $request->mechanics;
                $coupon->promo_start = date('Y-m-d', strtotime($request->promo_start. "+1 days"));
                $coupon->promo_end = date('Y-m-d', strtotime($request->promo_end. "+1 days"));
                $coupon->save();
               
                return response()->json(['status' => 'success', 'message' => 'Coupon Added'],200); 

                
                
            } else {

                return response()->json(['status' => 'danger', 'message' => 'Something went wrong. Please try again!'],422);
            }  

        } catch(\Exception $e) {

            return response()->json(['status' => 'danger',  'message'=>$e->getMessage()], 422);
            
        }
    }

}