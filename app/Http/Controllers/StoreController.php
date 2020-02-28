<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;
use App\Http\Requests\StoreRequest;
use App\Models\User;
use App\Models\Store;
use Carbon\Carbon;


class StoreController extends Controller
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
	public function getstore()
    {
        $user = Auth::user();

        return view('admin.store', compact('user'));
    }
    public function getStoreData(Request $request)
    {
        if ($request->wantsJson()) {

            $user = Auth::user();
    
            $store = Store::orderBy('created_at', 'DESC');
            
            if($store) {

                return Datatables::of($store)
                ->editColumn('code', function ($store) {
                    return $store->code;
                })
                ->editColumn('name', function ($store) {
                    return $store->name;
                })
                 ->editColumn('address', function ($store) {
                    return $store->address;
                })
                ->addColumn('date', function ($store) {
                    return date('F j, Y', strtotime($store->created_at)) . ' | ' . Carbon::parse($store->created_at)->diffForHumans();
                })
                ->addColumn('action', function ($store) {
                    return '<a href="" ng-click="form.editStore('.$store->id.')" class="btn btn-info btn-sm"><i class="fa fa-edit"></i> Edit</a>

                            <a href="" ng-click="form.deleteStore('.$store->id.')" class="btn btn-danger btn-sm"><i class="fa fa-times-circle"></i> Delete</a>';
                })
                ->addIndexColumn()
                ->rawColumns(['code','name','date','action'])
                ->make(true);

            }else{

                return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again.'),422);
            }

        } else{

            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }
    }
    public function postAddStore(StoreRequest $request)
    {
        try{

            if($request->wantsJson()) { 

                $user = Auth::user();

                $code_exists = Store::where('code', $request->code)->first();

                if($code_exists){

                     return response()->json(['status' => 'danger', 'message' => 'Store Code Exist!'],200); 

                }

                $stores = new Store;
                $stores->code = $request->code;
                $stores->name = $request->store;
                $stores->address = $request->address;
                $stores->save();
               
                return response()->json(['status' => 'success', 'message' => 'Store Added'],200); 

            } else {

                return response()->json(['status' => 'danger', 'message' => 'Something went wrong. Please try again!'],422);
            }  

        } catch(\Exception $e) {

            return response()->json(['status' => 'danger', 'message'=>$e->getMessage()], 422);
            
        }
    }
    public function getEditStore($store_id)
    {
        try{

            $user = Auth::user();

            $store = Store::where('id', $store_id)->first();

            if($store){

                $responseHtml = '<form class="edit-brand-form" name="editStoreFrm" ng-submit="form.submitEditStore()" autocomplete="off">
                            {{ csrf_field() }}
                            <div class="modal-header">
                                <h5 class="modal-title" id="editStoreModalLabel"> Edit Store</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <span id="edit_report_error"></span>
                                <div class="form-group">
                                  <label class="form-label" for="validation-username">Store Code</label>
                                  <input
                                    class="form-control"
                                    id="edit_store_id"
                                    name="category"
                                    type="hidden"
                                    value="'.$store->id.'"
                                  />
                                  <input
                                    class="form-control"
                                    id="edit_store_code"
                                    name="category"
                                    type="text"
                                    placeholder="Store Code"
                                    value="'.$store->code.'"
                                    required
                                  />
                                </div>
                                <div class="form-group">
                                  <label class="form-label" for="validation-username">Store Name</label>
                                  <input
                                    class="form-control"
                                    id="edit_store_name"
                                    name="category"
                                    type="text"
                                    placeholder="Store Name"
                                    value="'.$store->name.'"
                                    required
                                  />
                                </div>
                                <div class="form-group">
                                  <label class="form-label" for="validation-username">Store Address</label>
                                  <input
                                    class="form-control"
                                    id="edit_store_address"
                                    name="category"
                                    type="text"
                                    placeholder="Store Address"
                                    value="'.$store->address.'"
                                    required
                                  />
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary mr-2 px-5" id="edit_store_btn">Edit</button>
                            </div>
                    </form>';

                return response()->json(['responseHtml' => $responseHtml]);

            }

        } catch(\Exception $e) {

            return response()->json(['status'=>false, 'message'=>$e->getMessage()], 422);
            
        }
    }
    public function postEditStore(StoreRequest $request, $store_id)
    {
        try{

            if($request->wantsJson()){ 

                $user = Auth::user();

                $code_exists = Store::where('id', '!=', $store_id)->where('code', $request->code)->first();

                if($code_exists){

                     return response()->json(['status' => 'danger', 'message' => 'Store Code Exists'],200); 

                }

                $store = Store::where('id', $store_id)->first();

                if($store){

                    $store->code = $request->code;
                    $store->name = $request->store;
                    $store->address = $request->address;
                    $store->save();
                   
                    return response()->json(['status' => 'success', 'message' => 'Store Edited'],200); 
                }

            }else{

                return response()->json(['status' => 'danger', 'message' => 'Something went wrong. Please try again!'],422);
            }  

        } catch(\Exception $e) {

            return response()->json(['status' => 'danger', 'message'=>$e->getMessage()], 422);
            
        }
    }

    public function postDeleteStore(Request $request, $store_id){

        try{

            if($request->wantsJson()) {

                $user = Auth::user();
                $store = Store::where('id', $store_id)->first();

                if($store->user()->first()){

                    return response()->json(['status' => 'danger', 'message' => 'Store in use']);

                }

                if($store->product()->first()){

                    return response()->json(['status' => 'danger', 'message' => 'Store in use']);

                }

                if($store){

                    $store->delete();
                    return response()->json(['status' => 'success', 'message' => 'Store Deleted']);

                } 

            } else {

                return response()->json(['status' => 'error', 'message' => 'Something went wrong. Please try again!'],422);
            }

        } catch(\Exception $e) {

            return response()->json(['status'=>false, 'message'=>$e->getMessage()], 422);
            
        }
    }
}