<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;
use App\Http\Requests\ExpenseRequest;
use App\Models\User;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Store;
use Carbon\Carbon;

class ExpenseController extends Controller
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
	public function getExpense()
    {
        $user = Auth::user();

        $stores = Store::orderBy('id', 'DESC')->get();
        $categories = ExpenseCategory::orderBy('id', 'DESC')->get();

        return view('admin.expense.expense', compact('user', 'stores', 'categories'));
    }
    public function getExpenseData(Request $request) {

        if ($request->wantsJson()) {

            $user = Auth::user();
    
            $expenses = Expense::orderBy('created_at', 'DESC')->get();
            
            if($expenses) {

                return Datatables::of($expenses)
                ->editColumn('expense_date', function ($expenses) {
                    return $expenses->date;
                })
                ->editColumn('code', function ($expenses) {
                    return $expenses->code;
                })
                ->editColumn('store_id', function ($expenses) {

                    if($expenses->store){

                        return $expenses->store->code;

                    }

                })
                ->editColumn('category_id', function ($expenses) {
                    
                    if($expenses->category){

                         return $expenses->category->name;

                    }
                })
                ->editColumn('amount', function ($expenses) {
                    return '&#8369; '.number_format($expenses->amount);
                })
                ->editColumn('details', function ($expenses) {
                    return $expenses->details;
                })
                ->addColumn('date', function ($expenses) {
                    return date('F j, Y', strtotime($expenses->created_at)) . ' | ' . Carbon::parse($expenses->created_at)->diffForHumans();
                })
                ->addColumn('action', function ($expenses) {
                    return '<a href="" ng-click="form.editExpense('.$expenses->id.')" class="btn btn-info btn-sm"><i class="fa fa-edit"></i> Edit</a>

                            <a href="" ng-click="form.deleteExpense('.$expenses->id.')" class="btn btn-danger btn-sm"><i class="fa fa-times-circle"></i> Delete</a>';
                })
                ->addIndexColumn()
                ->rawColumns(['data','code','store_id','category_id','amount','details', 'date','action'])
                ->make(true);

            }else{

                return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again.'),422);
            }

        } else{

            return response()->json(array("result"=>false,"message"=>'Something went wrong. Please try again!'),422);
        }
                        
    }
    public function postAddExpense(ExpenseRequest $request)
    {
        try{

            if($request->wantsJson()) { 

                $user = Auth::user();

                $unique_code = "er-".date('Ymd-His');

                $expenses = new Expense;
                $expenses->date = date('Y-m-d', strtotime($request->date. "+1 days"));
                $expenses->code = $unique_code;
                $expenses->category_id = $request->category_id;
                $expenses->store_id = $request->store_id;
                $expenses->amount = $request->amount;
                $expenses->details = $request->details;
                $expenses->save();
               
                return response()->json(['status' => 'success', 'message' => 'Expense Added'],200); 


            } else {

                return response()->json(['status' => 'danger', 'message' => 'Something went wrong. Please try again!'],422);
            }  

        } catch(\Exception $e) {

            return response()->json(['status' => 'danger', 'message'=>$e->getMessage()], 422);
            
        }
    }
    public function getEditExpense($expense_id)
    {
        try{

            $user = Auth::user();

            $expense = Expense::where('id', $expense_id)->first();

            $stores = Store::orderBy('id', 'DESC')->get();

            $categories = ExpenseCategory::orderBy('id', 'DESC')->get();

            if($expense){

                $responseHtml = '<form class="edit-expense-category-form" name="editExpenseFrm" ng-submit="form.submitEditExpense()" autocomplete="off">
                            {{ csrf_field() }}
                            <div class="modal-header">
                                <h5 class="modal-title" id="editExpenseModalLabel"><i class="fa fa-edit"></i> Edit Expense</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <span id="edit_report_error"></span>
                                <div class="form-group">
                                  <label class="form-label" for="validation-username">Expense Date</label>
                                  <input
                                    class="form-control"
                                    id="edit_expense_id"
                                    name="expense"
                                    type="hidden"
                                    value="'.$expense->id.'"
                                  />
                                  <input
                                    class="form-control"
                                    name="data"
                                    id="edit_expense_date"
                                    type="date"
                                    placeholder="Expense Date"
                                    value="'.$expense->date.'"
                                  />
                                </div>
                                <div class="form-group">
                                  <label class="form-label" for="validation-username">Expense Category</label>
                                  <select
                                    class="form-control select2"
                                    id="edit_expense_category_id"
                                    name="expense_category_id"

                                  >';
                                    foreach($categories as $category){
                $responseHtml   .=   '<option value="'.$category->id.'"';
                                      if($expense->category_id == $category->id){

                $responseHtml   .=     'selected';

                                      }
                $responseHtml   .=   '>'.$category->name.'</option>';
                                    }
                $responseHtml   .=  '</select>

                                </div>
                                <div class="form-group">
                                  <label class="form-label" for="validation-username">Expense Category</label>
                                  <select
                                    class="form-control select2"
                                    id="edit_expense_store_id"
                                    name="expense_category"

                                  >';
                                    foreach($stores as $store){
                $responseHtml   .=   '<option value="'.$store->id.'"';
                                      if($expense->store_id == $store->id){

                $responseHtml   .=     'selected';

                                      }
                $responseHtml   .=   '>'.$store->code.' - '.$store->name.'</option>';
                                    }
                $responseHtml   .=  '</select>

                                </div>
                                <div class="form-group">
                                  <label class="form-label" for="validation-username">Expense amount</label>
                                  <input
                                    class="form-control"
                                    name="edit_amount"
                                    id="edit_expense_amount"
                                    type="number"
                                    placeholder="Expense Amount"
                                    value="'.$expense->amount.'"
                                  />
                                </div>
                                <div class="form-group">
                                  <label class="form-label" for="validation-username">Expense Details</label>
                                  <textarea
                                    class="form-control"
                                    name="details"
                                    id="edit_expense_details"
                                    placeholder="Expense Amount"
                                    rows="5"
                                  >'.$expense->details.'
                                  </textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary mr-2 px-5" id="edit_expense_btn">Edit</button>
                            </div>
                    </form>';

                    return response()->json(['status' => 'success', 'responseHtml' => $responseHtml]);

                }

        } catch(\Exception $e) {

            return response()->json(['status' => 'danger', 'message'=>$e->getMessage()], 422);
            
        }
    }
    public function postEditExpense(ExpenseRequest $request, $expense_id)
    {
        try{

            if($request->wantsJson()) { 

                $user = Auth::user();

                $expenses = Expense::where('id', $expense_id)->first();

                if($expenses){

                    $expenses->date = date('Y-m-d', strtotime($request->date));
                    $expenses->category_id = $request->category_id;
                    $expenses->store_id = $request->store_id;
                    $expenses->amount = $request->amount;
                    $expenses->details = $request->details;
                    $expenses->save();
                   
                    return response()->json(['status' => 'success', 'message' => 'Expense Edited'],200); 
                }

            } else {

                return response()->json(['status' => 'danger', 'message' => 'Something went wrong. Please try again!'],422);
            }  

        } catch(\Exception $e) {

            return response()->json(['status' => 'danger', 'message'=>$e->getMessage()], 422);
            
        }
    }
    public function postDeleteExpense(Request $request, $expense_id){

        try{

            if($request->wantsJson()) {

                $user = Auth::user();
                $expense = Expense::where('id', $expense_id)->first();

                if($expense){
                    
                    $expense->delete();
                    return response()->json(['status' => 'success','message' => 'Expense deleted'], 200);

                } 

            } else {

                return response()->json(['status' => 'danger', 'message' => 'Something went wrong. Please try again!'],422);
            }

        } catch(\Exception $e) {

            return response()->json(['status' => 'danger', 'message'=>$e->getMessage()], 422);
            
        }
    }

}