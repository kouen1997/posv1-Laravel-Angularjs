<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;
use App\Http\Requests\BrandRequest;
use App\Models\User;
use App\Models\Brand;
use Carbon\Carbon;


class BrandController extends Controller
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
	public function getBrand()
    {
        $user = Auth::user();

        return view('admin.product.brand', compact('user'));
    }
    public function getBrandData(Request $request)
    {
        if ($request->wantsJson()) {

            $user = Auth::user();
    
            $brands = Brand::orderBy('created_at', 'DESC');
            
            if($brands) {

                return Datatables::of($brands)
                ->editColumn('name', function ($brands) {
                    return $brands->name;
                })
                ->addColumn('date', function ($brands) {
                    return date('F j, Y', strtotime($brands->created_at)) . ' | ' . Carbon::parse($brands->created_at)->diffForHumans();
                })
                ->addColumn('action', function ($brands) {
                    return '<a href="" ng-click="form.editBrand('.$brands->id.')" class="btn btn-info btn-sm"><i class="fa fa-edit"></i> Edit</a>

                            <a href="" ng-click="form.deleteBrand('.$brands->id.')" class="btn btn-danger btn-sm"><i class="fa fa-times-circle"></i> Delete</a>';
                })
                ->addIndexColumn()
                ->rawColumns(['name','date','action'])
                ->make(true);

            }else{

                return response()->json(array('result' => 'danger', 'message' => 'Something went wrong. Please try again.'),422);
            }

        } else{

            return response()->json(array('result' => 'danger', 'message' => 'Something went wrong. Please try again!'),422);
        }
    }

    public function postAddBrand(BrandRequest $request)
    {
        try{

            if($request->wantsJson()) { 

                $user = Auth::user();

                if($user){

                    $brand_exists = Brand::where('name', $request->brand)->first();

                    if($brand_exists){

                         return response()->json(['status' => 'danger', 'message' => 'Brand Exists'],200); 

                    }else{

                        $brands = new Brand;
                        $brands->name = $request->brand;
                        $brands->save();
                       
                        return response()->json(['status' => 'success', 'message' => 'Brand Added'],200); 

                    }
                }

            } else {

                return response()->json(['status' => 'danger', 'message' => 'Something went wrong. Please try again!'],422);
            }  

        } catch(\Exception $e) {

            return response()->json(['status' => 'danger',  'message'=>$e->getMessage()], 422);
            
        }
    }

    public function getEditBrand($brand_id)
    {
        try{

            $user = Auth::user();

            if($user){

                $brand = Brand::where('id', $brand_id)->first();

                $responseHtml = '<form class="edit-brand-form" name="editBrandFrm" ng-submit="form.submitEditBrand()" autocomplete="off">
                            {{ csrf_field() }}
                            <div class="modal-header">
                                <h5 class="modal-title" id="editBrandModalLabel">Edit Brand</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <span id="edit_report_error"></span>
                                <div class="form-group">
                                  <label class="form-label" for="validation-username">Brand</label>
                                  <input
                                    class="form-control"
                                    id="edit_brand_id"
                                    name="category"
                                    type="hidden"
                                    placeholder="Brand Name"
                                    value="'.$brand->id.'"
                                  />
                                  <input
                                    class="form-control"
                                    id="edit_brand_name"
                                    name="category"
                                    type="text"
                                    placeholder="Brand Name"
                                    value="'.$brand->name.'"
                                    required
                                  />
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary mr-2 px-5" id="edit_brand_btn">Edit</button>
                            </div>
                    </form>';

                return response()->json(['responseHtml' => $responseHtml]);

            }

        } catch(\Exception $e) {

            return response()->json(['status'=> 'danger', 'message'=>$e->getMessage()], 422);
            
        }
    }
    public function postEditBrand(BrandRequest $request, $brand_id)
    {
        try{

            if($request->wantsJson()) { 

                $user = Auth::user();

                if($user){

                    $brand_exists = Brand::where('name', $request->brand)->first();

                    if($brand_exists){

                        return response()->json(['status' => 'danger', 'message' => 'Brand Exists'],200); 

                    }

                    $brands = Brand::where('id', $brand_id)->first();

                    if($brands){

                        $brands->name = $request->brand;
                        $brands->save();

                        return response()->json(['status' => 'success', 'message' => 'Brand Edited'],200);

                    } 
                    
                }

            } else {

                return response()->json(['status' => 'danger', 'message' => 'Something went wrong. Please try again!'],422);
            }  

        } catch(\Exception $e) {

            return response()->json(['status'=> 'danger', 'message'=>$e->getMessage()], 422);
            
        }
    }

    public function postDeleteBrand(Request $request, $brand_id){

        try{

            if($request->wantsJson()) {

                $user = Auth::user();
                $brand = Brand::where('id', $brand_id)->first();

                if($brand->products()->first()){

                    return response()->json(['status' => 'danger', 'message' => 'Brand in use'], 200);
                }

                if($brand){

                    $brand->delete();
                    return response()->json(['status' => 'success', 'message' => 'Brand deleted'], 200);

                } 

            } else {

                return response()->json(['status' => 'danger', 'message' => 'Something went wrong. Please try again!'],422);
            }

        } catch(\Exception $e) {

            return response()->json(['status'=> 'danger', 'message'=>$e->getMessage()], 422);
            
        }
    }

}