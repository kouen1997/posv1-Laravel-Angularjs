<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;
use App\Models\User;
use App\Models\Category;
use App\Http\Requests\CategoryRequest;
use Carbon\Carbon;


class CategoryController extends Controller
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
	public function getCategory()
    {
        $user = Auth::user();

        return view('admin.product.category')
                ->with('user', $user);
    }
    public function getCategoryData(Request $request) {

        if ($request->wantsJson()) {

            $user = Auth::user();
    
            $categories = Category::with('parent')->orderBy('created_at','DESC');
            
            if($categories) {

                return Datatables::of($categories)
                ->editColumn('name', function ($categories) {
                    return $categories->name;
                })
                ->editColumn('parent', function ($categories) {

                    if($categories->parent_id == NULL){
                        return 'Parent';
                    }else{
                        return $categories->child->name;
                    }
                    
                })
                ->addColumn('date', function ($categories) {
                    return date('F j, Y', strtotime($categories->created_at)) . ' | ' . Carbon::parse($categories->created_at)->diffForHumans();
                })
                ->addColumn('action', function ($categories) {
                    return '<a href="" ng-click="form.editCategory('.$categories->id.')" class="btn btn-info btn-sm"><i class="fa fa-edit"></i> Edit</a>

                            <a href="" ng-click="form.deleteCategory('.$categories->id.')" class="btn btn-danger btn-sm"><i class="fa fa-times-circle"></i> Delete</a>';
                })
                ->addIndexColumn()
                ->rawColumns(['name','parent','date','action'])
                ->make(true);

            }else{

                return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again.'),422);
            }

        } else{

            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }
                        
    }
    public function getCategoryPopulateOptionData(Request $request)
    {
       if ($request->wantsJson()) {

            $categories = Category::where('parent_id', NULL)->orderBy('created_at', 'DESC')->get();
            
            return response()->json([
                'categories' => $categories,
            ],200);


        }else{

            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }
    }
    public function postAddCategory(CategoryRequest $request)
    {
        try{

            if($request->wantsJson()) { 

                $user = Auth::user();

                $category_exists = Category::where('name', $request->category)->where('parent_id', $request->parent_category == '0' ? NULL : $request->parent_category)->first();

                if($category_exists){

                     return response()->json(['status' => 'danger', 'message' => 'Category Exists'],200); 

                }else{

                    $categories = new Category;
                    $categories->name = $request->category;
                    $categories->parent_id = $request->parent_category == '0' ? NULL : $request->parent_category;
                    $categories->save();

                    return response()->json(['status' => 'success', 'message' => 'Category added'],200); 

                }

            } else {

                return response()->json(['status' => 'danger', 'message' => 'Something went wrong. Please try again!'],422);
            }  

        } catch(\Exception $e) {

            return response()->json(['status' => 'danger', 'message'=>$e->getMessage()], 422);
            
        }
    }
    public function getEditCategory($category_id)
    {
        try{

            $user = Auth::user();

            $category = Category::where('id', $category_id)->first();

            $categories = Category::where('parent_id', NULL)->get();

            $responseHtml = '<form class="edit-category-form" name="editCategoryFrm" ng-submit="form.submitEditCategory()" autocomplete="off">
                        {{ csrf_field() }}
                        <div class="modal-header">
                            <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <span id="edit_report_error"></span>
                            <div class="form-group">
                              <label class="form-label" for="validation-username">Category</label>
                              <input
                                class="form-control"
                                id="edit_category_id"
                                name="category"
                                type="hidden"
                                placeholder="Category Name"
                                value="'.$category->id.'"
                              />
                              <input
                                class="form-control"
                                id="edit_category"
                                name="category"
                                type="text"
                                placeholder="Category Name"
                                value="'.$category->name.'"
                              />
                            </div>
                            <div class="form-group">
                              <label class="form-label" for="validation-username">Parent Category</label>
                              <select
                                class="form-control select2"
                                id="edit_parent_category"
                                name="parent_category"

                              >
                                <option value="0" selected>Parent</option>';
                                foreach($categories as $parent){
            $responseHtml   .=   '<option value="'.$parent->id.'"';
                                  if($parent->id == $category->parent_id){

            $responseHtml   .=     'selected';

                                  }
            $responseHtml   .=   '>'.$parent->name.'</option>';
                                }
            $responseHtml   .=  '</select>

                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary mr-2 px-5" id="edit_category_btn">Edit</button>
                            </div>
                        </div>
                </form>';

            return response()->json(['status' => 'success', 'responseHtml' => $responseHtml]);

            
        } catch(\Exception $e) {

            return response()->json(['status' => 'danger', 'message'=>$e->getMessage()], 422);
            
        }
    }
    public function postEditCategory(CategoryRequest $request, $category_id)
    {
        try{

            if($request->wantsJson()) { 

                $user = Auth::user();
                
                $category_exists = Category::where('name', $request->category)
                                            ->where('parent_id', $request->parent_category == '0' ? NULL : $request->parent_category)
                                            ->first();

                if($category_exists){

                    return response()->json(['status' => 'danger', 'message' => 'Category Exists'],200); 

                }else{

                    $categories = Category::where('id', $category_id)->first();
                    $categories->name = $request->category;

                    if($categories->parent_id != NULL){

                        $categories->parent_id = $request->parent_category == '0' ? NULL : $request->parent_category;

                    }

                    $categories->save();

                    if($categories->parent_id == NULL){

                        if($request->parent_category != 0){

                            $message = 'Category Name Edited | Cannot edit parent category';

                        }else{

                            $message = 'Category Edited';
                        }

                    }else{

                        $message = 'Category Edited';
                    }

                    return response()->json(['status' => 'success', 'message' => $message],200); 

                }
                
            } else {

                return response()->json(['status' => 'danger', 'message' => 'Something went wrong. Please try again!'],422);
            }  

        } catch(\Exception $e) {

            return response()->json(['status' => 'danger', 'message'=> $e->getMessage()], 422);
            
        }
    }
    public function postDeleteCategory(Request $request, $category_id){


        try{
                
            if($request->wantsJson()) {

                $user = Auth::user();

                $category = Category::where('id', $category_id)->first();

                $exists = getCategoryExists($category_id);

                if($category->parent_id == NULL){

                    if($exists['has_child']){

                        return response()->json(['status' => 'danger', 'message' => 'Cannot delete category has child']);

                    }

                    if($exists['has_product_parent']){

                        return response()->json(['status' => 'danger', 'message' => 'Category is in use']);
                    }

                }elseif($category->parent_id != NULL){

                    if($exists['has_product_child']){

                        return response()->json(['status' => 'danger', 'message' => 'Category is in use']);
                    }
                }

                if($category){

                    $category->delete();

                    return response()->json(['status' => 'success', 'message' => 'Category Deleted']);

                } 

            } else {

                return response()->json(['status' => 'danger', 'message' => 'Something went wrong. Please try again!'],422);
            }

        } catch(\Exception $e) {

            return response()->json(['status' => 'danger', 'message'=>$e->getMessage()], 422);
            
        }
    }
}