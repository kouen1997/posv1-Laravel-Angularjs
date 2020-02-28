<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;
use App\Models\User;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Products;
use App\Models\Coupon;
use App\Models\Purchase;
use App\Models\Inventory;
use Image;
use DB;
use Picqer;
use Carbon\Carbon;


class PurchaseController extends Controller
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
	public function getPurchase()
    {
        $user = Auth::user();
        return view('admin.purchase.purchase')->with('user', $user);
    }

    public function getPurchaseData(Request $request)
    {
        if ($request->wantsJson()) {

            $user = Auth::user();
    
            $purchases = Purchase::orderBy('created_at', 'DESC');
            
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
                ->addColumn('coupon', function ($purchases) {

                    if(!empty($purchases->coupon_id)){

                        return $purchases->coupon->code;

                    }else{

                        return 'No coupon applied';
                    }

                })
                ->addColumn('purchases', function ($purchases) {
                    return '<a href="" ng-click="form.showPurchasess('.$purchases->id.')">Show Purchases</a>';
                })
                ->addColumn('items', function ($purchases) {
                    return $purchases->grand_items;
                })
                ->addColumn('cash', function ($purchases) {
                    return '&#8369; '.number_format($purchases->cash_payment, 2);
                })
                ->addColumn('tax', function ($purchases) {
                    return '&#8369; '.number_format($purchases->grand_tax, 2);
                })
                ->addColumn('total', function ($purchases) {
                    return '&#8369; '.number_format($purchases->grand_total, 2);
                })
                ->addColumn('date', function ($purchases) {
                    return date('F j, Y', strtotime($purchases->created_at)) . ' | ' . Carbon::parse($purchases->created_at)->diffForHumans();
                })
                ->addColumn('action', function ($purchases) {
                    return '<a href="'.asset('/purchase/return/'.$purchases->invoice_id).'"class="btn btn-danger">Return</a>';
                })
                ->addIndexColumn()
                ->rawColumns(['invoice','cashier','store','coupon','purchases','items','cash','tax','total','date','action'])
                ->make(true);

            }else{

                return response()->json(array('result' => 'danger', 'message' => 'Something went wrong. Please try again.'),422);
            }

        } else{

            return response()->json(array('result' => 'danger', 'message' => 'Something went wrong. Please try again!'),422);
        }
    }

    public function getItemPurchaseData($purchase_id){

        $purchase = Purchase::where('id', $purchase_id)->first();

        try{

            if($purchase){

                $products = json_decode($purchase->orders, true);

                $responeHtml = '';

                $responeHtml .= '<div class="card" style="padding:20px;">
                                    <h5 class="font-weight-bold">Invoice</h5>
                                    <small>'.$purchase->store->name.'</small>
                                    <br>
                                    <small>Invoice #: '.$purchase->invoice_id.'</small>';

                                    if(!empty($purchase->coupon_id)){

                $responeHtml .=         '<small class="float-right">Coupon #: '.$purchase->coupon->code.'</small>';

                                    }

                $responeHtml .=     '<br>
                                    <div class="row">
                                        <div class="col-6 col-md-6 col-lg-6">
                                            <p class="font-weight-bold">
                                                Product Name
                                            </p>
                                        </div>
                                        <div class="col-3 col-md-3 col-lg-3">
                                            <p class="font-weight-bold">
                                                Qty
                                            </p>
                                        </div>
                                        <div class="col-3 col-md-3 col-lg-3">
                                            <p class="font-weight-bold">
                                                Price
                                            </p>
                                        </div>
                                    </div>
                                    ';

                                    for($i = 0; $i < count($products[0]['product_id']); $i++){

                $responeHtml .=         '<div class="row">
                                            <div class="col-6 col-md-6 col-lg-6">';
                $responeHtml .=                 $products[0]['product_name'][$i];
                $responeHtml .=             '</div>';
                $responeHtml .=             '<div class="col-3 col-md-3 col-lg-3 float-right">';
                $responeHtml .=                 'x'.$products[0]['qty'][$i];
                $responeHtml .=             '</div>';
                $responeHtml .=             '<div class="col-3 col-md-3 col-lg-3 float-right">';
                $responeHtml .=                 $products[0]['price'][$i];
                $responeHtml .=             '</div>';
                $responeHtml .=         '</div>';

                                    }

                $responeHtml .=  '</div>';

                return response()->json(['status' => 'success', 'responeHtml' => $responeHtml], 200);

            }

        } catch(\Exception $e) {

            return response()->json(['status' => 'danger',  'message'=>$e->getMessage()], 422);
            
        }

    }

    public function getPurchaseReturn($invoice_id){

        $user = Auth::user();

        $purchase = Purchase::where('invoice_id', $invoice_id)->first();
        
        if(!$purchase){

            abort(404);

        }

        return view('admin.purchase.return')
                ->with('user', $user)
                ->with('purchase', $purchase)
                ->with('orders', json_decode($purchase->orders, true));
    }

}