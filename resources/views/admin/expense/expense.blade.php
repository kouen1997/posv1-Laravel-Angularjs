@extends('layouts.master')

@section('title', 'Expense')

@section('header_scripts')
<link href="{{ URL::asset('assets/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/modules/izitoast/css/iziToast.min.css') }}">
<style>
    .table-responsive {
        font-size:15px!important;
    }
</style>
@stop

@section('content')

<div class="main-content" style="min-height: 668px;" ng-app="expenseApp" ng-controller="ExpenseCtrl as form">
  <section class="section">
    <div class="section-header">
      <h1>Expense</h1>
      <span class="btn btn-primary section-header-breadcrumb" ng-click="form.addExpense()">Add Brand</span>
    </div>

    <div class="section-body">
        <div class="col-12 col-md-12 col-lg-12">
          <div class="card">
            <div class="card-header">
              <h4>Expense</h4>
              
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <div id="loading">
                    <h3 class="text-center"><i class="fas fa-spinner fa-spin"></i> Please wait...</h3>
                </div>
                <table id="content-table" class="table table-md bg-ligh"> 
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Expense Date</th>
                      <th>Code</th>
                      <th>Store</th>
                      <th>Category</th>
                      <th>Amount</th>
                      <th>Details</th>
                      <th>Date</th>
                      <th>Action</th>
                    </tr>
                  </thead>  
                </table>
              </div>
            </div>
          </div>
        </div>
    </div>
  </section>

  <!-- Add Expense -->

    <div class="modal fade" id="addExpenseModal" tabindex="-1" role="dialog" aria-labelledby="addExpenseModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <form class="form-box ng-pristine ng-invalid ng-invalid-required" name="addExpenseForm" ng-submit="form.submitAddExpense(addExpenseForm.$valid)">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="addExpenseModalLabel">Add Expense </h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
              <div class="modal-body">
                <span id="report_error"></span>
                  <div class="form-group">
                    <label class="form-label" for="validation-username">Expense Date</label>
                    <input
                      class="form-control"
                      name="date"
                      type="date"
                      placeholder="Expense Date"
                      ng-model="form.date"
                    />
                  </div>
                  <div class="form-group">
                    <label class="form-label" for="validation-username">Expense Category</label>
                    <select
                      class="form-control select2"
                      name="category_id"
                      ng-model="form.category_id"
                    >
                      <option selected disabled>Choose Expense Category</option>
                      @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="form-group">
                    <label class="form-label" for="validation-username">Store</label>
                    <select
                      class="form-control select2"
                      name="store_id"
                      ng-model="form.store_id"
                    >
                      <option selected disabled>Choose Store</option>
                      @foreach($stores as $store)
                        <option value="{{ $store->id }}">{{ $store->code }} - {{ $store->name }}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="form-group">
                    <label class="form-label" for="validation-username">Expense amount</label>
                    <input
                      class="form-control"
                      name="amount"
                      type="number"
                      placeholder="Expense Amount"
                      ng-model="form.amount"
                    />
                  </div>

                  <div class="form-group">
                    <label class="form-label" for="validation-username">Expense Details</label>
                    <textarea
                      class="form-control"
                      name="details"
                      placeholder="Expense Details"
                      ng-model="form.details"
                      rows="5"
                    >
                    </textarea>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="submit" class="btn btn-primary mr-2 px-5" id="add_expense_btn">Add</button>
              </div>
          </div>
        </form>
      </div>
    </div>

  <!-- End Add Expense -->

  <!-- Edit Expense -->

    <div class="modal fade" id="editExpenseModal" tabindex="-1" role="dialog" aria-labelledby="editExpenseModal" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content expense-edit">

        </div>
      </div>
    </div>

  <!-- End Edit Expense -->

  <!-- Delete Expense -->

  <div class="modal fade" id="deleteExpenseModal" tabindex="-1" role="dialog" aria-labelledby="deleteExpenseModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form class="form-box ng-pristine ng-invalid ng-invalid-required" name="deleteExpenseForm" ng-submit="form.submitDeleteExpense(deleteExpenseForm.$valid)">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-danger" id="addExpenseModalLabel"><i class="fa fa-times-circle"></i> Delete Expense </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
            <div class="modal-body text-center">
                Are you sure you want to delete this Expense ?
            </div>
            <div class="modal-footer">
                <input type="hidden" name="expense_id" id="delete_expense_id">
                <button type="submit" class="btn btn-primary mr-2 px-5" id="delete_expense_btn">Delete</button>
            </div>
        </div>
      </form>
    </div>
  </div>

  <!-- End Delete Expense -->

</div>

@endsection

@section('footer_scripts')

<script src="{{ URL::asset('assets/plugins/angular/angular.min.js')  }}"></script>
<script src="{{ URL::asset('assets/plugins/angular/angular.filter.min.js')  }}"></script>
<script src="{{ URL::asset('assets/plugins/angular/angular-animate.min.js')  }}"></script>
<script src="{{ URL::asset('assets/plugins/angular/angular-aria.min.js')  }}"></script>
<script src="{{ URL::asset('assets/plugins/angular/angular-messages.min.js')  }}"></script>
<script src="{{ URL::asset('assets/plugins/angular/angular-material.min.js')  }}"></script>
<script src="{{ URL::asset('assets/plugins/angular/angular-sanitize.js')  }}"></script>

<script src="{{ URL::asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ URL::asset('assets/modules/izitoast/js/iziToast.min.js') }}"></script>

<script type="text/javascript">
    (function () {
        var expenseApp = angular.module('expenseApp', ['angular.filter']);
        expenseApp.controller('ExpenseCtrl', function ($scope, $http, $sce, $compile) {

            var vm = this;

            getdata();
            function getdata() {
                $("#content-table").dataTable().fnDestroy(); 
                $('#loading').show();
                $("#content-table").hide();
                    
                angular.element(document).ready( function () {

                    var tbl = $('#content-table').DataTable({
                        pageLength: 5,
                        processing: true,
                        serverSide: true,
                        stateSave: true,
                        ajax: {
                            url: '/expense-data',
                            data: function (data) {

                                for (var i = 0, len = data.columns.length; i < len; i++) {
                                    if (! data.columns[i].search.value) delete data.columns[i].search;
                                    if (data.columns[i].searchable === true) delete data.columns[i].searchable;
                                    if (data.columns[i].orderable === true) delete data.columns[i].orderable;
                                    if (data.columns[i].data === data.columns[i].name) delete data.columns[i].name;
                                }
                                delete data.search.regex;
                            }
                        },
                        lengthChange: false,
                        info: false,
                        autoWidth: false,
                        columnDefs: [
                            {
                                render: function (data, type, full, meta) {
                                    return "<div>" + data + "</div>";
                                },
                                targets: [0]
                            }
                         ],
                        columns: [
                            {data: 'DT_RowIndex', name: 'id', orderable: true, searchable: false},
                            {data: 'expense_date', name: 'date', orderable: true, searchable: false},
                            {data: 'code', name: 'code', orderable: false, searchable: true},
                            {data: 'store_id', name: 'store_id', orderable: false, searchable: false},
                            {data: 'category_id', name: 'category_id', orderable: false, searchable: false},
                            {data: 'amount', name: 'amount', orderable: true, searchable: false},
                            {data: 'details', name: 'details', orderable: false, searchable: false},
                            {data: 'date', name: 'created_at', orderable: false, searchable: false},
                            {data: 'action', name: 'action', orderable: false, searchable: false},
                        ],
                        "createdRow": function (row, data, index) {
                            $compile(row)($scope);
                        },
                        order: [[1, 'desc']],
                        "initComplete": function(settings, json) { 
                               $('#loading').delay( 300 ).hide(); 
                               $("#content-table").delay( 300 ).show(); 
                        } 
                    });

                });

            }

            vm.addExpense = function(){

                $('#addExpenseModal').appendTo('body').modal('show');

            };

            vm.submitAddExpense = function () {
                event.preventDefault();

                $('#add_expense_btn').prop('disabled', true);
                $('#add_expense_btn').html('Saving... <i class="fa fa-spinner fa-spin"></i>');

                $http({
                    method: 'POST',
                    url: '/add-expense',
                    data: JSON.stringify({
                        date: vm.date,
                        category_id: vm.category_id,
                        store_id: vm.store_id,
                        amount: vm.amount,
                        details: vm.details

                    })
                 }).then(function successCallback(response) {

                    $('#add_expense_btn').prop('disabled', false);
                    $('#add_expense_btn').html('Add');

                    if (response.data.status == 'success'){

                           iziToast.success({
                              title: 'Success',
                              message: response.data.message,
                              position: 'topRight',
                          }); 

                          $('.form-control').val('');
                          $('#addExpenseModal').appendTo("body").modal('hide');

                          getdata();

                    } else {

                        iziToast.error({
                            title: 'Error',
                            message: response.data.message,
                            position: 'topRight',
                        }); 

                    }

                }, function errorCallback(response) {

                    $('#add_expense_btn').prop('disabled', false);
                    $('#add_expense_btn').html('Add');

                    if (response.data.status){

                        iziToast.warning({
                            title: 'Warning',
                            message: response.data.message,
                            position: 'topRight',
                        }); 

                    } else {

                        var errors = [];
                        angular.forEach(response.data.errors, function(message, key){
                            errors.push(message[0]);
                        });

                        iziToast.warning({
                            title: 'Warning',
                            message: errors.toString().split(",").join("\n \n"),
                            position: 'topRight',
                        }); 
                    }

                });

            };

            vm.editExpense = function(expense_id){

                $http({
                    method: 'GET',
                    url: '/edit-expense/'+expense_id
                }).then(function successCallback(response) {
                    
                    if (response.data.status == 'success'){

                      $('#editExpenseModal').appendTo("body").modal('show');
                      $('.expense-edit').show().html($compile(response.data.responseHtml)($scope));

                    }

                }, function errorCallback(response) {
                    
                    if (response.data.status){

                        iziToast.warning({
                            title: 'Warning',
                            message: response.data.message,
                            position: 'topRight',
                        }); 

                    } else {

                        var errors = [];
                        angular.forEach(response.data.errors, function(message, key){
                            errors.push(message[0]);
                        });

                        iziToast.warning({
                            title: 'Warning',
                            message: errors.toString().split(",").join("\n \n"),
                            position: 'topRight',
                        }); 
                    }
              });

            };

            vm.submitEditExpense = function () {
                event.preventDefault();

                $('#edit_expense_btn').prop('disabled', true);
                $('#edit_expense_btn').html('Saving... <i class="fa fa-spinner fa-spin"></i>');

                var expense_id = document.getElementById("edit_expense_id").value;
                var date = document.getElementById("edit_expense_date").value;
                var category_id = document.getElementById("edit_expense_category_id").value;
                var store_id = document.getElementById("edit_expense_store_id").value;
                var amount = document.getElementById("edit_expense_amount").value;
                var details = document.getElementById("edit_expense_details").value;

                $http({
                    method: 'POST',
                    url: '/edit-expense/'+expense_id,
                    data: JSON.stringify({
                        date: date,
                        category_id: category_id,
                        store_id: store_id,
                        amount: amount,
                        details: details
                    })
                 }).then(function successCallback(response) {

                    $('#edit_expense_btn').prop('disabled', false);
                    $('#edit_expense_btn').html('Edit');

                    if (response.data.status == 'success'){

                        iziToast.success({
                            title: 'Success',
                            message: response.data.message,
                            position: 'topRight',
                        }); 

                        $('#editExpenseModal').appendTo("body").modal('hide');
                        $('.form-control').val('');

                        getdata();

                    } else {

                        iziToast.error({
                            title: 'Error',
                            message: response.data.message,
                            position: 'topRight',
                        }); 

                    }

                }, function errorCallback(response) {

                    $('#edit_expense_btn').prop('disabled', false);
                    $('#edit_expense_btn').html('Edit');

                    if (response.data.status){

                        iziToast.warning({
                            title: 'Warning',
                            message: response.data.message,
                            position: 'topRight',
                        }); 

                    } else {

                        var errors = [];
                        angular.forEach(response.data.errors, function(message, key){
                            errors.push(message[0]);
                        });

                        iziToast.warning({
                            title: 'Warning',
                            message: errors.toString().split(",").join("\n \n"),
                            position: 'topRight',
                        }); 
                    }

                });

            };

            vm.deleteExpense = function(expense_id){
              event.preventDefault();

              $('#deleteExpenseModal').appendTo("body").modal('show');
              $(".modal-footer #delete_expense_id").val(expense_id);

            };

            vm.submitDeleteExpense = function () {

              $('#delete_expense_btn').attr('disabled', true).append(' <i class="fa fa-spinner fa-pulse "></i>');
              var expense_id = document.getElementById("delete_expense_id").value;

              $http({
                  method: 'POST',
                  url: '/delete-expense/'+expense_id,
              }).then(function successCallback(response) {

                  if(response.data.status == 'success'){
                      console.log(response.data);

                      iziToast.success({
                          title: 'Success',
                          message: response.data.message,
                          position: 'topRight',
                      }); 

                      $('#delete_expense_btn').attr('disabled', false).html('Delete'); 
                      $('#deleteExpenseModal').appendTo("body").modal('hide');
                      
                      getdata();
                  }
                  
              }, function errorCallback(response) {
                  
                  $('#delete_expense_btn').attr('disabled', false).html('Delete')

                  if (response.data.status){

                        iziToast.warning({
                            title: 'Warning',
                            message: response.data.message,
                            position: 'topRight',
                        }); 

                    } else {

                        var errors = [];
                        angular.forEach(response.data.errors, function(message, key){
                            errors.push(message[0]);
                        });

                        iziToast.warning({
                            title: 'Warning',
                            message: errors.toString().split(",").join("\n \n"),
                            position: 'topRight',
                        }); 
                    }
                  
              });

            };

      });

    })();
</script>
@stop