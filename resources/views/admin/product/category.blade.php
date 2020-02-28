@extends('layouts.master')

@section('title', 'Category')

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

<div class="main-content" style="min-height: 668px;" ng-app="categoryApp" ng-controller="CategoryCtrl as form">
  <section class="section">
    <div class="section-header">
      <h1>Category</h1>
      <span class="btn btn-primary section-header-breadcrumb" ng-click="form.addCategory()">Add Category</span>
    </div>

    <div class="section-body">
        <div class="col-12 col-md-12 col-lg-12">
          <div class="card">
            <div class="card-header">
              <h4>Category</h4>
              
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
                          <th>Parent</th>
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

  <!-- Add Category -->

    <div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <form class="form-box ng-pristine ng-invalid ng-invalid-required" name="addCategoryForm" ng-submit="form.submitAddCategory(addCategoryForm.$valid)">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="addCategoryModalLabel">Add Category</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label class="form-label" for="validation-username">Category</label>
                <input class="form-control" name="category" type="text" placeholder="Category Name" ng-model="form.category"/>
              </div>
              <div class="form-group">
                <label class="form-label" for="validation-username">Parent Category</label>
                <select class="form-control select2" name="parent_category" ng-model="form.parent_category" required>
                  <option value="0" selected>Parent</option>
                  <option ng-repeat="category in categories" value="@{{ category.id }}">@{{ category.name }}</option>
                </select>
              </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary mr-2 px-5" id="add_category_btn">Add</button>
            </div>
          </div>
        </form>
      </div>
    </div>

  <!-- End Add Category -->

  <!-- Edit Category -->

    <div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editCategoryModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content category-edit">
          </div>
        </div>
    </div>

  <!-- End Edit Category -->

  <!-- Delete Category -->

    <div class="modal fade" id="deleteCategoryModal" tabindex="-1" role="dialog" aria-labelledby="DeleteCategoryModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <form class="form-box" name="deleteCategoryForm" ng-submit="form.submitDeleteCategory()">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title text-danger" id="DeleteCategoryModalLabel">Delete Category</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
              <div class="modal-body text-center">
                  Are you sure you want to delete this category?
              </div>
              <div class="modal-footer">
                  <input type="hidden" name="category_id" id="delete_category_id" class="delete_category_id">
                  <input type="hidden" name="parent_id" id="delete_parent_id" class="delete_parent_id">
                  <button type="submit" class="btn btn-primary mr-2 px-5" id="delete_category_btn">Delete</button>
              </div>
          </div>
        </form>
      </div>
    </div>

  <!-- Delete Category -->

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
  var categoryApp = angular.module('categoryApp', ['angular.filter']);
  categoryApp.controller('CategoryCtrl', function ($scope, $http, $sce, $compile) {

      var vm = this;

      getdata();
      function getdata() {
          $("#content-table").dataTable().fnDestroy(); 
          $('#loading').show();
          $("#content-table").hide();
              
          angular.element(document).ready( function () {

              var tbl = $('#content-table').DataTable({
                  pageLength: 10,
                  processing: true,
                  serverSide: true,
                  stateSave: true,
                  ajax: {
                      url: '/category-data',
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
                      {data: 'parent', name: 'parent_id', orderable: false, searchable: true},
                      {data: 'date', name: 'created_at', orderable: true, searchable: false},
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
      function populateOption(){

          $http.get('/category-populate-option-data').success(function(response) {

              $scope.categories = response.categories;
              
          });

      }
      vm.addCategory = function(){

          populateOption();
          $('#addCategoryModal').appendTo('body').modal('show');

      };
      vm.submitAddCategory = function () {
          event.preventDefault();

          $('#add_category_btn').prop('disabled', true);
          $('#add_category_btn').html('Saving... <i class="fa fa-spinner fa-spin"></i>');

          $http({
              method: 'POST',
              url: '/addcategory',
              data: JSON.stringify({
                  category: vm.category,
                  parent_category: vm.parent_category
              })
           }).then(function successCallback(response) {

              $('#add_category_btn').prop('disabled', false);
              $('#add_category_btn').html('Add');

              if (response.data.status == 'success'){

                  iziToast.success({
                      title: 'Success',
                      message: response.data.message,
                      position: 'topRight',
                  });

                  $('.form-control').val('');
                  $('#addCategoryModal').appendTo("body").modal('hide');

                  getdata();

              } else {

                  iziToast.error({
                      title: 'Error',
                      message: response.data.message,
                      position: 'topRight',
                  }); 

              }

          }, function errorCallback(response) {

              $('#add_category_btn').prop('disabled', false);
              $('#add_category_btn').html('Add');

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

      vm.editCategory = function(category_id){
            
          $http({
              method: 'GET',
              url: '/editcategory/'+category_id
          }).then(function successCallback(response) {
              
              if (response.data.status == 'success'){

                  $('#editCategoryModal').appendTo("body").modal('show');
                  $('.category-edit').show().html($compile(response.data.responseHtml)($scope));
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

      vm.submitEditCategory = function () {
        event.preventDefault();

        $('#edit_category_btn').prop('disabled', true);
        $('#edit_category_btn').html('Saving... <i class="fa fa-spinner fa-spin"></i>');

        var category_id = document.getElementById("edit_category_id").value;
        var category = document.getElementById("edit_category").value;
        var parent_category = document.getElementById("edit_parent_category").value;

        $http({
            method: 'POST',
            url: '/editcategory/'+category_id,
            data: JSON.stringify({
                category: category,
                parent_category: parent_category
            })
         }).then(function successCallback(response) {

            $('#edit_category_btn').prop('disabled', false);
            $('#edit_category_btn').html('Edit');

            if (response.data.status == 'success'){

                iziToast.success({
                    title: 'Success',
                    message: response.data.message,
                    position: 'topRight',
                });

                $('#edit_category_btn').attr('disabled', false).html('Save'); 
                $('#editCategoryModal').appendTo("body").modal('hide');
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

            $('#edit_category_btn').prop('disabled', false);
            $('#edit_category_btn').html('Edit');

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

    vm.deleteCategory = function(category_id){
      event.preventDefault();

      $('#deleteCategoryModal').appendTo("body").modal('show');
      $(".modal-footer #delete_category_id").val(category_id);

    };

    vm.submitDeleteCategory = function () {

        $('#delete_category_btn').attr('disabled', true).append(' <i class="fa fa-spinner fa-pulse "></i>');

        var delete_category_id = document.getElementById("delete_category_id").value;

        $http({
            method: 'POST',
            url: '/deletecategory/'+delete_category_id,
        }).then(function successCallback(response) {

            $('#delete_category_btn').prop('disabled', false);
            $('#delete_category_btn').html('Delete');

            if(response.data.status == 'success'){
                console.log(response.data);

                iziToast.success({
                    title: 'Success',
                    message: response.data.message,
                    position: 'topRight',
                }); 

                $('#deleteCategoryModal').appendTo("body").modal('hide');
                
                getdata();
               
            }else{

                iziToast.error({
                    title: 'Error',
                    message: response.data.message,
                    position: 'topRight',
                }); 
            }
            
        }, function errorCallback(response) {
            
            $('#delete_category_btn').prop('disabled', false);
            $('#delete_category_btn').html('Delete');

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
