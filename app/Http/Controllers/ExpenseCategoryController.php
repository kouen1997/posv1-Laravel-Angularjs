<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;
use App\Http\Requests\ExpenseCategoryRequest;
use App\Models\User;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use Carbon\Carbon;


class ExpenseCategoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
	public function getExpenseCategory()
    {
        $user = Auth::user();

        return view('admin.expense.expense_category', compact('user'));
    }

    public function getExpenseCategoryData(Request $request)
    {
        if ($request->wantsJson()) {

            $user = Auth::user();
    
            $category = ExpenseCategory::orderBy('created_at', 'DESC');
            
            if($category) {

                return Datatables::of($category)
                ->editColumn('name', function ($category) {
                    return $category->name;
                })
                ->addColumn('date', function ($category) {
                    return date('F j, Y', strtotime($category->created_at)) . ' | ' . Carbon::parse($category->created_at)->diffForHumans();
                })
                ->addColumn('action', function ($category) {
                    return '<a href="" ng-click="form.editExpenseCategory('.$category->id.')" class="btn btn-info btn-sm"><i class="fa fa-edit"></i> Edit</a>

                            <a href="" ng-click="form.deleteExpenseCategory('.$category->id.')" class="btn btn-danger btn-sm"><i class="fa fa-times-circle"></i> Delete</a>';
                })
                ->addIndexColumn()
                ->rawColumns(['name','date','action'])
                ->make(true);

            }else{

                return response()->json(array('result' => 'danger' ,'message'=>'Something went wrong. Please try again.'),422);
            }

        } else{

            return response()->json(array('result' => 'danger' ,'message'=>'Something went wrong. Please try again!'),422);
        }
    }

    public function postAddExpenseCategory(ExpenseCategoryRequest $request)
    {
        try{

            if($request->wantsJson()) { 

                $user = Auth::user();

                $expense_category_exists = ExpenseCategory::where('name', $request->expense_category)->first();

                if($expense_category_exists){

                     return response()->json(['status' => 'danger', 'message' => 'Expense Category Exists'],200); 

                }

                $expenses = new ExpenseCategory;
                $expenses->name = $request->expense_category;
                $expenses->save();
               
                return response()->json(['status' => 'success', 'message' => 'Expense Category Added'],200); 


            } else {

                return response()->json(['status' => 'danger', 'message' => 'Something went wrong. Please try again!'],422);
            }  

        } catch(\Exception $e) {

            return response()->json(['status'=> 'danger', 'message' => $e->getMessage()], 422);
            
        }
    }
    public function getEditExpenseCategory($expense_category_id)
    {
        try{

            $user = Auth::user();

            if($user){

                $expense_category = ExpenseCategory::where('id', $expense_category_id)->first();

                if($expense_category){

                    $responseHtml = '<form class="edit-expense-category-form" name="editExpenseCategoryFrm" ng-submit="form.submitEditExpenseCategory()" autocomplete="off">
                                {{ csrf_field() }}
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editExpenseCategoryModalLabel"><i class="fa fa-edit"></i> Edit Expense Category</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <span id="edit_report_error"></span>
                                    <div class="form-group">
                                      <label class="form-label" for="validation-username">Expense Name</label>
                                      <input
                                        class="form-control"
                                        id="edit_expense_category_id"
                                        name="expense"
                                        type="hidden"
                                        placeholder="Brand Name"
                                        value="'.$expense_category->id.'"
                                      />
                                      <input
                                        class="form-control"
                                        id="edit_expense_category_name"
                                        name="category"
                                        type="text"
                                        placeholder="Expense Name"
                                        value="'.$expense_category->name.'"
                                      />
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary mr-2 px-5" id="edit_expense_category_btn">Submit</button>
                                </div>
                        </form>';

                    return response()->json(['status' => 'success', 'responseHtml' => $responseHtml]);

                }

            }

        } catch(\Exception $e) {

            return response()->json(['status'=> 'danger', 'message'=>$e->getMessage()], 422);
            
        }
    }
    public function postEditExpenseCategory(ExpenseCategoryRequest $request, $expense_category_id)
    {
        try{

            if($request->wantsJson()) { 

                $user = Auth::user();

                if($user){

                    $expense_category = ExpenseCategory::where('name', $request->expense_category)->first();

                    if($expense_category){

                         return response()->json(['status' => 'danger', 'message' => 'Expense Category Exists'],200); 

                    }

                    $expenses = ExpenseCategory::where('id', $expense_category_id)->first();
                    $expenses->name = $request->expense_category;
                    $expenses->save();

                    return response()->json(['status' => 'success', 'message' => 'Expense Category Edited'],200); 

                }

            } else {

                return response()->json(['status' => 'danger', 'message' => 'Something went wrong. Please try again!'],422);
            }  

        } catch(\Exception $e) {

            return response()->json(['status'=> 'danger', 'message'=>$e->getMessage()], 422);
            
        }
    }

    public function postDeleteExpenseCategory(Request $request, $expense_category_id){

        try{

            if($request->wantsJson()) {

                $user = Auth::user();

                $expenseCategory = ExpenseCategory::where('id', $expense_category_id)->first();

                if(!$expenseCategory){

                    return response()->json(['status' => 'danger', 'message' => 'Expense Category not exists'], 200); 
                }

                $expenseCategoryExists = Expense::where('category_id', $expense_category_id)->first();

                if($expenseCategoryExists){

                    return response()->json(['status' => 'danger', 'message' => 'Expense Category cannot be deleted'], 200); 
                }

                $expenseCategory->delete();

                return response()->json(['status' => 'success', 'message' => 'Expense Categorqy deleted'], 200); 

            } else {

                return response()->json(['status' => 'danger', 'message' => 'Something went wrong. Please try again!'],422);
            }

        } catch(\Exception $e) {

            return response()->json(['status'=> 'danger', 'message'=>$e->getMessage()], 422);
            
        }
    }




}