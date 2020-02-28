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


class PosController extends Controller
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
	public function getPos()
    {
        $user = Auth::user();
        $categories = Category::where('parent_id', NULL)->orderBy('created_at', 'DESC')->get();
        $brands = Brand::orderBy('created_at', 'DESC')->get();

        return view('pos')->with('user', $user)->with('categories', $categories)->with('brands', $brands);
    }

    public function getPosProduct(Request $request) {

    	if ($request->wantsJson()) {

	    	$products = new Products;

            if($request->has('search_brand')){

                if($request->search_brand != 'all'){

                    $products = $products->where('brand_id', $request->search_brand);

                }elseif($request->search_brand == 'all'){

                    $products = $products;

                }

            }

            if($request->has('search_category')){

                if($request->search_category != 'all'){

                    $products = $products->where('parent_id', $request->search_category);

                }elseif($request->search_category == 'all'){

                    $products = $products;

                } 

            }

	        $products = $products->where('qty', '!=', 0)->orderBy('created_at', 'DESC')->get();
	        return response()->json([
	            'products' => $products
	        ], 200);

	    } else {

            return response()->json(array('status' => 'danger' ,'message' => 'Something went wrong. Please try again!'),422);
        }
    }

    public function getPosGiftCard(Request $request, $giftCard)
    {
        $coupon = Coupon::where('code', $giftCard)
                    ->whereDate('promo_start', '<=', Carbon::today()->toDateString())
                    ->whereDate('promo_end', '>=', Carbon::today()->toDateString())
                    ->first();

        if(!$coupon){

            return response()->json(['status' => 'danger' ,'message' => 'Coupon does not exists'], 200);
        }

        if($request->grand_total < $coupon->minimum){

            return response()->json(['status' => 'danger' ,'message' => 'Minimum purchase of '.$coupon->minimum], 200);
        }

        return response()->json(['status' => 'success', 'coupon' => $coupon ,'message' => 'Coupon Applied'], 200);

    }
    public function getPosPurchase(Request $request)
    {
        
        try{

            if($request->wantsJson()) {

                $user = Auth::user();

                if($request->cash_payment < $request->grand_total){

                    return response()->json(['status' => 'danger', 'message' => 'Insufficient Cash'],200);
                }

                $unique_code = "INV-".date('yHisv');

                $product = array();
                $product[] = array(
                    'product_id' => $request->product_id,
                    'product_name' => $request->product_name,
                    'qty' => $request->qty,
                    'price' => $request->price
                );

                $purchase = new Purchase;
                $purchase->user_id = $user->id;
                $purchase->store_id = $user->store->id;
                $purchase->coupon_id = $request->coupon_id;
                $purchase->invoice_id = $unique_code;
                $purchase->orders = json_encode($product);
                $purchase->cash_payment = $request->cash_payment;
                $purchase->grand_items = $request->grand_items;
                $purchase->grand_tax = $request->grand_tax;
                $purchase->grand_total = $request->grand_total;
                $purchase->save();

                $products = json_decode($purchase->orders, true);

                $unique_inventory_code = "INVTRY-".date('yHisv');

                for($i = 0; $i < count($products[0]['product_id']); $i++){

                    $inventory = new Inventory;
                    $inventory->user_id = $user->id;
                    $inventory->store_id = $user->store->id;
                    $inventory->product_id = $products[0]['product_id'][$i];
                    $inventory->inventory_id = $unique_inventory_code;
                    $inventory->qty = $products[0]['qty'][$i];
                    $inventory->status = 'PURCHASE';
                    $inventory->save();

                    $decrement_qty = Products::where('id', $products[0]['product_id'][$i])->decrement('qty', $products[0]['qty'][$i]);

                }

                $responeHtml = '';

                $responeHtml .= '<div class="card" style="padding:20px;">
                                    <h5 class="font-weight-bold">Invoice</h5>
                                    <small>'.$user->store->name.'</small>
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

                return response()->json(['status' => 'success', 'responeHtml' => $responeHtml, 'message' => 'Purchase'], 200);

            } else {

                return response()->json(['status' => 'danger', 'message' => 'Something went wrong. Please try again!'],422);
            }  

        } catch(\Exception $e) {

            return response()->json(['status' => 'danger',  'message'=>$e->getMessage()], 422);
            
        }
    }

}