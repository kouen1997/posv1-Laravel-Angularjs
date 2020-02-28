<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;
use App\Http\Requests\InventoryINRequest;
use App\Models\User;
use App\Models\Store;
use App\Models\Products;
use App\Models\Inventory;
use Carbon\Carbon;


class InventoryController extends Controller
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
	public function getInventoryIn()
    {
        $user = Auth::user();
        $stores = Store::orderBy('created_at', 'DESC')->get();

        return view('admin.inventory.in')
        		->with('user', $user)
        		->with('stores', $stores);
    }

    public function getInventoryInData(Request $request)
    {
     	if ($request->wantsJson()) {

            $user = Auth::user();
    
            $inventory = Inventory::with(['product','store'])->where('status', 'IN')->orderBy('created_at', 'DESC');
            
            if($inventory) {

                return Datatables::of($inventory)
                ->editColumn('product', function ($inventory) {
                    return $inventory->product->code;
                })
                ->editColumn('store', function ($inventory) {
                    return $inventory->store->code;
                })
                ->editColumn('quantity', function ($inventory) {
                    return $inventory->qty;
                })
                ->addColumn('date', function ($inventory) {
                    return date('F j, Y', strtotime($inventory->created_at)) . ' | ' . Carbon::parse($inventory->created_at)->diffForHumans();
                })
                ->addIndexColumn()
                ->rawColumns(['product','store','quantity','date'])
                ->make(true);

            }else{

                return response()->json(array('result' => 'danger', 'message' => 'Something went wrong. Please try again.'),422);
            }

        } else{

            return response()->json(array('result' => 'danger', 'message' => 'Something went wrong. Please try again!'),422);
        }
    }

    public function getInventoryStoreData(Request $request)
    {
        $search = $request->search;

	    if(empty($search)){

	         $stores = Store::orderby('created_at','DESC')->get();

	    }else{

	        $stores = Store::where('code', 'like', '%' .$search . '%')->orderby('created_at','DESC')->get();
	    }

	    $response = array();
	    foreach($stores as $store){

	        $response[] = array(

	            'id' => $store->id,

	            'text' => $store->code
	        );

	    }

	    echo json_encode($response);
	    exit;
    }

    public function getPopulateProductData(Request $request, $store_id)
    {
        try{

            if($request->wantsJson()) { 

                $user = Auth::user();

                $products = Products::where('store_id', $store_id)->get();

                $response = array();
                if(count($products) > 0){

                    foreach ($products as $product) {
                        
                        $response[] = array(

				            'id' => $product->id,

				            'text' => $product->name
				        );

                    }

                }

                return response()->json(['data' => $response], 200);

            } else {

                return response()->json(['status' => 'danger', 'message' => 'Something went wrong. Please try again!'],422);
            }  

        } catch(\Exception $e) {

            return response()->json(['status' => 'danger', 'message'=>$e->getMessage()], 422);
            
        }

    }

    public function getInventoryAddIn(InventoryINRequest $request)
    {
    	if($request->wantsJson()) { 

    		$user = Auth::user();

    		$unique_inventory_code = "INVTRY-".date('yHisv');

    		$inventory = new Inventory;
            $inventory->user_id = $user->id;
            $inventory->store_id = $request->store_id;
            $inventory->product_id = $request->product_id;
            $inventory->inventory_id = $unique_inventory_code;
            $inventory->qty = $request->qty;
            $inventory->status = 'IN';
            $inventory->save();

            Products::where('id', $inventory->product_id)->increment('qty', $inventory->qty);

            return response()->json(['status' => 'success', 'message' => 'Quantity added'], 200);

    	} else {

            return response()->json(['status' => 'danger', 'message' => 'Something went wrong. Please try again!'],422);
        }  
    }
}