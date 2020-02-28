@extends('layouts.master')

@section('title', 'Brand')

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

<div class="main-content" style="min-height: 668px;" ng-app="brandApp" ng-controller="BrandCtrl as form">
  <section class="section">
    <div class="section-header">
      <h1>Brand</h1>
      <span class="btn btn-primary section-header-breadcrumb" ng-click="form.addBrand()">Add Brand</span>
    </div>

    <div class="section-body">
        <div class="col-12 col-md-12 col-lg-12">
          <div class="card">
            <div class="card-header">
              <h4>Brand</h4>
              
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

  <!-- Add Brand -->

    <div class="modal fade" id="addBrandModal" tabindex="-1" role="dialog" aria-labelledby="addBrandModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <form class="form-box ng-pristine ng-invalid ng-invalid-required" name="addBrandForm" ng-submit="form.submitAddBrand(addBrandForm.$valid)">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="addBrandModalLabel">Add Brand</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
              <div class="modal-body">
                <span id="report_error"></span>
                  <div class="form-group">
                    <label class="form-label" for="validation-username">Brand Name</label>
                    <input class="form-control" name="brand" type="text" placeholder="Brand Name" ng-model="form.brand"
                     required/>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="submit" class="btn btn-primary mr-2 px-5" id="add_brand_btn">Add</button>
              </div>
          </div>
        </form>
      </div>
    </div>

  <!-- End Add Brand -->

  <!-- Edit Brand -->

    <div class="modal fade" id="editBrandModal" tabindex="-1" role="dialog" aria-labelledby="editBrandModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content brand-edit">

          </div>
        </div>
    </div>

  <!-- End Edit Brand -->

  <!-- Delete Brand -->

  <div class="modal fade" id="deleteBrandModal" tabindex="-1" role="dialog" aria-labelledby="deleteBrandModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form class="form-box ng-pristine ng-invalid ng-invalid-required" name="deleteBrandForm" ng-submit="form.submitDeleteBrand(deleteBrandForm.$valid)">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-danger" id="deleteBrandModalLabel">Delete Brand</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
            <div class="modal-body text-center">
                Are you sure you want to delete this brand?
            </div>
            <div class="modal-footer">
                <input type="hidden" name="brand_id" id="delete_brand_id">
                <button type="submit" class="btn btn-primary mr-2 px-5" id="delete_brand_btn">Delete</button>
            </div>
        </div>
      </form>
    </div>
  </div>

  <!-- End Delete Brand -->

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
        var brandApp = angular.module('brandApp', ['angular.filter']);
        brandApp.controller('BrandCtrl', function ($scope, $http, $sce, $compile) {

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
                            url: '/brand-data',
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

            vm.addBrand = function(){

                $('#addBrandModal').appendTo('body').modal('show');

            };

            vm.submitAddBrand = function () {
                event.preventDefault();

                $('#add_brand_btn').prop('disabled', true);
                $('#add_brand_btn').html('Saving... <i class="fa fa-spinner fa-spin"></i>');

                $http({
                    method: 'POST',
                    url: '/addbrand',
                    data: JSON.stringify({
                        brand: vm.brand
                    })
                 }).then(function successCallback(response) {

                    $('#add_brand_btn').prop('disabled', false);
                    $('#add_brand_btn').html('Add');

                    if (response.data.status == 'success'){

                          iziToast.success({
                              title: 'Success',
                              message: response.data.message,
                              position: 'topRight',
                          });

                          $('.form-control').val('');
                          $('#addBrandModal').appendTo("body").modal('hide');

                          getdata();

                    } else {

                        iziToast.error({
                            title: 'Error',
                            message: response.data.message,
                            position: 'topRight',
                        });

                    }

                }, function errorCallback(response) {

                    $('#add_brand_btn').prop('disabled', false);
                    $('#add_brand_btn').html('Add');

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

            vm.editBrand = function(brand_id){

                $http({
                    method: 'GET',
                    url: '/editbrand/'+brand_id
                }).then(function successCallback(response) {
                    
                    $('#editBrandModal').appendTo("body").modal('show');
                    $('.brand-edit').show().html($compile(response.data.responseHtml)($scope));

                }, function errorCallback(response) {
                    
              });

            };

            vm.submitEditBrand = function () {
                event.preventDefault();

                $('#edit_brand_btn').prop('disabled', true);
                $('#edit_brand_btn').html('Saving... <i class="fa fa-spinner fa-spin"></i>');

                var brand_id = document.getElementById('edit_brand_id').value;
                var brand = document.getElementById('edit_brand_name').value;

                $http({
                    method: 'POST',
                    url: '/editbrand/'+brand_id,
                    data: JSON.stringify({
                        brand: brand
                    })
                 }).then(function successCallback(response) {

                    $('#edit_brand_btn').prop('disabled', false);
                    $('#edit_brand_btn').html('Edit');

                    if (response.data.status == 'success'){

                        iziToast.success({
                            title: 'Success',
                            message: response.data.message,
                            position: 'topRight',
                        });

                        $('#edit_brand_btn').attr('disabled', false).html('Save'); 
                        $('#editBrandModal').appendTo("body").modal('hide');
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

                    $('#edit_brand_btn').prop('disabled', false);
                    $('#edit_brand_btn').html('Edit');

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

            vm.deleteBrand = function(brand_id){
              event.preventDefault();

              $('#deleteBrandModal').appendTo("body").modal('show');
              $(".modal-footer #delete_brand_id").val(brand_id);

            };

            vm.submitDeleteBrand = function () {

              $('#delete_brand_btn').attr('disabled', true).append(' <i class="fa fa-spinner fa-pulse "></i>');
              var brand_id = document.getElementById("delete_brand_id").value;

              $http({
                  method: 'POST',
                  url: '/deletebrand/'+brand_id,
              }).then(function successCallback(response) {

                  if(response.data.status == 'success'){
                      console.log(response.data);

                      iziToast.success({
                          title: 'Success',
                          message: response.data.message,
                          position: 'topRight',
                      }); 

                      $('#delete_brand_btn').attr('disabled', false).html('Delete'); 
                      $('#deleteBrandModal').appendTo("body").modal('hide');
                      
                      getdata();
                  
                  }else{

                      $('#delete_brand_btn').attr('disabled', false).html('Delete'); 
                      
                      iziToast.error({
                          title: 'Error',
                          message: response.data.message,
                          position: 'topRight',
                      }); 
                  }
                  
              }, function errorCallback(response) {
                  
                  $('#delete_brand_btn').attr('disabled', false).html('Delete')

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