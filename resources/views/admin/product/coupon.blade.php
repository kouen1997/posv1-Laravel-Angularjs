@extends('layouts.master')

@section('title', 'Coupon')

@section('header_scripts')
<link href="{{ URL::asset('assets/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/modules/izitoast/css/iziToast.min.css') }}">
<style>
    .table-responsive {
        font-size:15px!important;
    }
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        margin: 0; 
    }
</style>
@stop

@section('content')

<div class="main-content" style="min-height: 668px;" ng-app="couponApp" ng-controller="CouponCtrl as form">
  <section class="section">
    <div class="section-header">
      <h1>Coupon</h1>
      <span class="btn btn-primary section-header-breadcrumb" ng-click="form.addCoupon()">Add Coupon</span>
    </div>

    <div class="section-body">
        <div class="col-12 col-md-12 col-lg-12">
          <div class="card">
            <div class="card-header">
              <h4>Coupon</h4>
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
                        <th>Code</th>
                        <th>Name</th>
                        <th>Min Spend</th>
                        <th>Discount</th>
                        <th>Mechanics</th>
                        <th>Start</th>
                        <th>End</th>
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

    <div class="modal fade" id="addCouponModal" tabindex="-1" role="dialog" aria-labelledby="addCouponModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <form class="form-box ng-pristine ng-invalid ng-invalid-required" name="addCouponForm" ng-submit="form.submitAddCoupon(addCouponForm.$valid)">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="addCouponModalLabel">Add Coupon</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
              <div class="modal-body">
                <div class="form-group">
                  <div class="row">
                    <div class="col-6 col-md-6 col-lg-6">
                      <label class="form-label" for="validation-username">Coupon Code</label>
                      <input class="form-control" name="coupon_code" type="text" placeholder="Coupon Code" ng-model="form.coupon_code"
                       required/>
                    </div>
                    <div class="col-6 col-md-6 col-lg-6">
                      <label class="form-label" for="validation-username">Coupon Name</label>
                      <input class="form-control" name="coupon_name" type="text" placeholder="Coupon Name" ng-model="form.coupon_name"
                       required/>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="row">
                    <div class="col-6 col-md-6 col-lg-6">
                      <label class="form-label" for="validation-username">Min. Spen</label>
                      <input class="form-control" name="min_spend" type="number" placeholder="Min. Spend" ng-model="form.min_spend"
                       required/>
                    </div>
                    <div class="col-6 col-md-6 col-lg-6">
                      <label class="form-label" for="validation-username">Discount</label>
                      <input class="form-control" name="discount" type="number" placeholder="Discount" ng-model="form.discount"
                       required/>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="form-label" for="validation-username">Mechanics</label>
                  <textarea class="form-control" name="mechanics" placeholder="Mechanics" cols="6" ng-model="form.mechanics"
                   required/></textarea>
                </div>
                <div class="form-group">
                  <div class="row">
                    <div class="col-6 col-md-6 col-lg-6">
                      <label class="form-label" for="validation-username">Promo Start</label>
                      <input class="form-control" name="promo_start" type="date" placeholder="Promo Start" ng-model="form.promo_start"
                       required/>
                    </div>
                    <div class="col-6 col-md-6 col-lg-6">
                      <label class="form-label" for="validation-username">Promo End</label>
                      <input class="form-control" name="promo_end" type="date" placeholder="Promo End" ng-model="form.promo_end"
                       required/>
                    </div>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="submit" class="btn btn-primary mr-2 px-5" id="add_coupon_btn">Add</button>
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
        var couponApp = angular.module('couponApp', ['angular.filter']);
        couponApp.controller('CouponCtrl', function ($scope, $http, $sce, $compile) {

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
                            url: '/coupon-data',
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
                            {data: 'code', name: 'code', orderable: false, searchable: true},
                            {data: 'name', name: 'name', orderable: false, searchable: true},
                            {data: 'minimum', name: 'minimum', orderable: false, searchable: false},
                            {data: 'discount', name: 'discount', orderable: false, searchable: false},
                            {data: 'mechanics', name: 'mechanics', orderable: false, searchable: false},
                            {data: 'promo_start', name: 'promo_start', orderable: false, searchable: false},
                            {data: 'promo_end', name: 'promo_end', orderable: false, searchable: false},
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

            vm.addCoupon = function(){

                $('#addCouponModal').appendTo('body').modal('show');

            };

            vm.submitAddCoupon = function () {
                event.preventDefault();

                $('#add_coupon_btn').prop('disabled', true);
                $('#add_coupon_btn').html('Saving... <i class="fa fa-spinner fa-spin"></i>');

                $http({
                    method: 'POST',
                    url: '/add-coupon',
                    data: JSON.stringify({
                        coupon_code: vm.coupon_code,
                        coupon_name: vm.coupon_name,
                        min_spend: vm.min_spend,
                        discount: vm.discount,
                        mechanics: vm.mechanics,
                        promo_start: vm.promo_start,
                        promo_end: vm.promo_end
                    })
                 }).then(function successCallback(response) {

                    $('#add_coupon_btn').prop('disabled', false);
                    $('#add_coupon_btn').html('Add');

                    if (response.data.status == 'success'){

                          iziToast.success({
                              title: 'Success',
                              message: response.data.message,
                              position: 'topRight',
                          });

                          $('.form-control').val('');
                          $('#addCouponModal').appendTo("body").modal('hide');

                          getdata();

                    } else {

                        iziToast.error({
                            title: 'Error',
                            message: response.data.message,
                            position: 'topRight',
                        });

                    }

                }, function errorCallback(response) {

                    $('#add_coupon_btn').prop('disabled', false);
                    $('#add_coupon_btn').html('Add');

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