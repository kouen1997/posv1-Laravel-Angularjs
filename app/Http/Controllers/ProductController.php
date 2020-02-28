<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;
use App\Http\Requests\AddProductsRequest;
use App\Models\User;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Products;
use App\Models\Inventory;
use Session;
use Image;
use Picqer;
use Carbon\Carbon;


class ProductController extends Controller
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

   	public function getProduct()
    {
        $user = Auth::user();

        return view('admin.product.product', compact('user'));
    }

    public function getProductData(Request $request) {

        if ($request->wantsJson()) {

            $user = Auth::user();
    
            $products = Products::orderBy('created_at', 'DESC');
            
            if($products) {

                return Datatables::of($products)
                ->addColumn('image', function ($products) {

                    return '<img src="'.url('/assets/static/images/products/'.$products->image).'" alt="'.$products->name.'" height="100" width="100">';
                    
                })
                ->editColumn('code', function ($products) {
                    return $products->code;
                })
                ->editColumn('name', function ($products) {

                    return $products->name;
                    
                })
                ->addColumn('brand', function ($products) {

                    if($products->brand){

                        return $products->brand->name;

                    }else{

                        return "No Brand";

                    }

                })
                ->addColumn('parent', function ($products) {

                    if($products->parent){

                        if(!empty($products->child_id)){

                            return $products->parent->name." ~ ".$products->child->name;

                        }else{

                            return $products->parent->name;
                        }
                        

                    }else{

                        return "No Category";
                    }
                
                })
                ->addColumn('qty', function ($products) {

                        if($products->qty > 100){

                            return '<span class="badge badge-primary">'.number_format($products->qty).'</span>';

                        }else{

                            return '<span class="badge badge-danger">'.number_format($products->qty).'</span>';
                        }

                        
                })
                ->addColumn('unit', function ($products) {
                        return $products->unit;
                })
                ->addColumn('price', function ($products) {
                        return '&#8369; '.number_format($products->price);
                })
                ->addColumn('date', function ($products) {
                    return date('F j, Y', strtotime($products->created_at)) . ' | ' . Carbon::parse($products->created_at)->diffForHumans();
                })
                ->addColumn('action', function ($products) {
                    return '<a href="'.url('/editproduct/'.$products->id).'" class="btn btn-info btn-sm"><i class="fa fa-edit"></i> Edit</a>

                            <a href="" ng-click="form.deleteProduct('.$products->id.')" class="btn btn-danger btn-sm"><i class="fa fa-times-circle"></i> Delete</a>';
                })
                ->addIndexColumn()
                ->rawColumns(['code','name','image','brand','category','qty','unit','price','date','action'])
                ->make(true);

            }else{

                return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again.'),422);
            }

        } else{

            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }
                        
    }

    public function getAddProduct()
    {
        $user = Auth::user();
        $brands = Brand::orderBy('id', 'DESC')->get();
        $parent_categories = Category::where('parent_id', NULL)->orderBy('created_at', 'DESC')->get();
        
        return view('admin.product.add_product', compact('user','brands','parent_categories'));
    }

    public function getCategoryPupulateData(Request $request)
    {
        try{

            if($request->wantsJson()) { 

                $user = Auth::user();

                $responseHtml = "";

                $sub_categories = Category::where('parent_id', $request->category)->get();

                if(count($sub_categories) > 0){

                    $responseHtml = '<option selected disabled>Choose Sub Category</option>';
                    foreach ($sub_categories as $sub_category) {
                        
                        $responseHtml .= '<option value="'.$sub_category->id.'">'.$sub_category->name.'</option>';

                    }

                }else{

                    $responseHtml = '<option selected disabled>No sub category</option>';
                }

                return response()->json(['status' => 'success', 'responseHtml' => $responseHtml],200);

            } else {

                return response()->json(['status' => 'danger', 'message' => 'Something went wrong. Please try again!'],422);
            }  

        } catch(\Exception $e) {

            return response()->json(['status' => 'danger', 'message'=>$e->getMessage()], 422);
            
        }

    }

    public function postAddProduct(AddProductsRequest $request)
    {
        try{

            $user = Auth::user();

            $product = new Products;

            $product->user_id = $user->id;
            $product->store_id = $user->store_id;


            $unique_inventory_code = "INVTRY-".date('yHisv');

            $unique_code = "PROD-".date('yHis');

            if ($request->hasFile('image')) {

                $productImage = $request->file('image');
                $strippedName = str_replace(' ', '', $productImage->getClientOriginalName());
                $photoName = date('Y-m-d-H-i-s').$strippedName;

                $moveFile = Image::make($productImage->getRealPath())->resize(400, 400);
                $moveFile->save(public_path().'/assets/static/images/products/'.$photoName, 60);

                $product->image = $photoName;
            }
            $product->name = $request->name;
            $product->code = $unique_code;
            $product->type = $request->type;
            $product->brand_id = $request->brand;
            $product->parent_id = $request->parent_category;
            $product->child_id = $request->child_category;
            $product->unit = $request->unit;
            $product->qty = $request->qty;
            $product->cost = $request->cost;
            $product->price = $request->price;
            $product->featured = $request->featured ? '1' : '0';
            $product->promotional_price = $request->promotional_price;
            $product->promotional_start = $request->promotional_start;
            $product->promotional_end = $request->promotional_end;
            $product->tax_method = $request->tax_method;
            $product->tax = $request->tax;
            $product->details = $request->details;
            $product->save();

            $inventory = new Inventory;
            $inventory->user_id = $user->id;
            $inventory->store_id = $user->store->id;
            $inventory->product_id = $product->id;
            $inventory->inventory_id = $unique_inventory_code;
            $inventory->qty = $request->qty;
            $inventory->status = 'IN';
            $inventory->save();

            Session::flash('success','Product successfully added');
            return redirect('/product/products');

        } catch(\Exception $e) {

            return response()->json(['status'=>false, 'message'=>$e->getMessage()], 422);
            
        }

    }

}