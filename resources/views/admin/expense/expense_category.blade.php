@extends('layouts.master')

@section('title', 'Expense Category')

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

<div class="main-content" style="min-height: 668px;" ng-app="expensecategoryApp" ng-controller="ExpenseCategoryCtrl as form">
  <section class="section">
    <div class="section-header">
      <h1>Expense Category</h1>
      <span class="btn btn-primary section-header-breadcrumb" ng-click="form.addExpenseCategory()">Add Expense Category</span>
    </div>

    <div class="section-body">
        <div class="col-12 col-md-12 col-lg-12">
          <div class="card">
            <div class="card-header">
              <h4>Expense Category</h4>
              
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
                      <th>Name</th>
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

  <!-- Add Expense Category -->

    <div class="modal fade" id="addExpenseCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addExpenseCategoryModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <form class="form-box ng-pristine ng-invalid ng-invalid-required" name="addExpenseCategoryForm" ng-submit="form.submitAddExpenseCategory(addExpenseCategoryForm.$valid)">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="addExpenseCategoryModalLabel">Add Expense Category</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
              <div class="modal-body">
                <span id="report_error"></span>
                  <div class="form-group">
                    <label class="form-label" for="validation-username">Expense Category Name</label>
                    <input
                      class="form-control"
                      name="expense_category"
                      type="text"
                      placeholder="Expense Category Name"
                      ng-model="form.expense_category"
                    />
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="submit" class="btn btn-primary mr-2 px-5" id="add_expense_category_btn">Add</button>
              </div>
          </div>
        </form>
      </div>
    </div>

  <!-- End Add Expense Category -->

  <!-- Edit Expense Category -->

    <div class="modal fade" id="editExpenseCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editExpenseCategoryModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content expense-category-edit">

          </div>
        </div>
    </div>

  <!-- End Edit Expense Category -->

  <!-- Delete Expense Category -->

  <div class="modal fade" id="deleteExpenseCategoryModal" tabindex="-1" role="dialog" aria-labelledby="deleteExpenseCategoryModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form class="form-box ng-pristine ng-invalid ng-invalid-required" name="deleteExpenseCategoryForm" ng-submit="form.submitDeleteExpenseCategory(deleteExpenseCategoryForm.$valid)">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-danger" id="addExpenseCategoryModalLabel"><i class="fa fa-times-circle"></i> Delete Expense Category</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
            <div class="modal-body text-center">
                Are you sure you want to delete this Expense Category?
            </div>
            <div class="modal-footer">
                <input type="hidden" name="expense_category_id" id="delete_expense_category_id">
                <button type="submit" class="btn btn-primary mr-2 px-5" id="delete_expense_category_btn">Delete</button>
            </div>
        </div>
      </form>
    </div>
  </div>

  <!-- End Delete Expense Category -->

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
        var expensecategoryApp = angular.module('expensecategoryApp', ['angular.filter']);
        expensecategoryApp.controller('ExpenseCategoryCtrl', function ($scope, $http, $sce, $compile) {

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
                            url: '/expense-category-data',
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
                            {data: 'name', name: 'name', orderable: false, searchable: true},
                            {data: 'date', name: 'created_at', orderable: true, searchable: false},
                            {data: 'action', name: 'action', orderable: false, searchable: false}
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

            vm.addExpenseCategory = function(){

                $('#addExpenseCategoryModal').appendTo('body').modal('show');

            };

            vm.submitAddExpenseCategory = function () {
                event.preventDefault();

                $('#add_expense_category_btn').prop('disabled', true);
                $('#add_expense_category_btn').html('Saving... <i class="fa fa-spinner fa-spin"></i>');

                $http({
                    method: 'POST',
                    url: '/add-expense-category',
                    data: JSON.stringify({
                        expense_category: vm.expense_category
                    })
                 }).then(function successCallback(response) {

                    $('#add_expense_category_btn').prop('disabled', false);
                    $('#add_expense_category_btn').html('Add');

                    if (response.data.status == 'success'){

                          iziToast.success({
                              title: 'Success',
                              message: response.data.message,
                              position: 'topRight',
                          }); 

                          vm.expense_category = '';
                          $('#addExpenseCategoryModal').appendTo("body").modal('hide');

                          getdata();

                    } else { 

                        iziToast.error({
                            title: 'Error',
                            message: response.data.message,
                            position: 'topRight',
                        }); 


                    }

                }, function errorCallback(response) {

                    $('#add_expense_category_btn').prop('disabled', false);
                    $('#add_expense_category_btn').html('Add');

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

            vm.editExpenseCategory = function(expense_category_id){

                $http({
                    method: 'GET',
                    url: '/edit-expense-category/'+expense_category_id,
                }).then(function successCallback(response) {
                    
                    if(response.data.status == 'success'){

                      $('#editExpenseCategoryModal').appendTo("body").modal('show');
                      $('.expense-category-edit').show().html($compile(response.data.responseHtml)($scope));

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

            vm.submitEditExpenseCategory = function () {
                event.preventDefault();

                $('#edit_expense_category_btn').prop('disabled', true);
                $('#edit_expense_category_btn').html('Saving... <i class="fa fa-spinner fa-spin"></i>');

                var expense_category_id = document.getElementById("edit_expense_category_id").value;
                var expense_category = document.getElementById("edit_expense_category_name").value;

                $http({
                    method: 'POST',
                    url: '/edit-expense-category/'+expense_category_id,
                    data: JSON.stringify({
                        expense_category: expense_category
                    })
                 }).then(function successCallback(response) {

                    $('#edit_expense_category_btn').prop('disabled', false);
                    $('#edit_expense_category_btn').html('Submit');

                    if (response.data.status == 'success'){

                        iziToast.success({
                            title: 'Success',
                            message: response.data.message,
                            position: 'topRight',
                        });

                        $('#edit_expense_category_btn').attr('disabled', false).html('Save'); 
                        $('#editExpenseCategoryModal').appendTo("body").modal('hide');
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

                    $('#edit_expense_category_btn').prop('disabled', false);
                    $('#edit_expense_category_btn').html('Submit');

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

            vm.deleteExpenseCategory = function(expense_category_id){
                event.preventDefault();

                $('#deleteExpenseCategoryModal').appendTo("body").modal('show');
                $(".modal-footer #delete_expense_category_id").val(expense_category_id);

            };

            vm.submitDeleteExpenseCategory = function () {

              $('#delete_expense_category_btn').attr('disabled', true).append(' <i class="fa fa-spinner fa-pulse "></i>');
              var expense_category_id = document.getElementById("delete_expense_category_id").value;

              $http({
                  method: 'POST',
                  url: '/delete-expense-category/'+expense_category_id,
              }).then(function successCallback(response) {

                  $('#delete_expense_category_btn').attr('disabled', false).html('Delete'); 
                  $('#deleteExpenseCategoryModal').appendTo("body").modal('hide');

                  if(response.data.status == 'success'){
                      console.log(response.data);

                      iziToast.success({
                          title: 'Success',
                          message: response.data.message,
                          position: 'topRight',
                      }); 
                      
                      getdata();
                  
                  }else{

                      iziToast.error({
                          title: 'Error',
                          message: response.data.message,
                          position: 'topRight',
                      }); 
                  }
                  
              }, function errorCallback(response) {
                  
                  $('#delete_expense_category_btn').attr('disabled', false).html('Delete')

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