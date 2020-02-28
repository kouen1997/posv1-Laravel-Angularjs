@extends('layouts.master')

@section('title', 'IN')

@section('header_scripts')
<link href="{{ URL::asset('assets/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/modules/izitoast/css/iziToast.min.css') }}">
<link href="{{ URL::asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css">
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
    select{
      background-color: #fdfdff;
      border-color: #e4e6fc;
    }
</style>
@stop

@section('content')

<div class="main-content" style="min-height: 668px;" ng-app="inApp" ng-controller="InCtrl as form">
  <section class="section">
    <div class="section-header">
      <h1>IN</h1>
      <span class="btn btn-primary section-header-breadcrumb" ng-click="form.addIN()">Add IN</span>
    </div>

    <div class="section-body">
        <div class="col-12 col-md-12 col-lg-12">
          <div class="card">
            <div class="card-header">
              <h4>IN</h4>
              
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
                      <th>Product</th>
                      <th>Store</th>
                      <th>Quantity</th>
                      <th>Date</th>
                    </tr>
                  </thead>  
                </table>
              </div>
            </div>
          </div>
        </div>
    </div>
  </section>

  <!-- Add IN -->

    <div class="modal fade" id="addINModal" role="dialog" aria-labelledby="addINModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form class="form-box ng-pristine ng-invalid ng-invalid-required" name="addINForm" ng-submit="form.submitAddIN(addINForm.$valid)">
                <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="addINModalLabel">Add IN</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label" for="store">Store</label><br>
                            <select name="store" id="store" style="width: 100%;" ng-model="form.store" ng-change="form.populateProduct()" required>
                                <option selected hidden>Select Store</option>
                            </select>
                        </div>
                        <div class="form-group">
                          <label class="form-label" for="product">Products</label>
                          <select name="product" id="product" style="width: 100%;" ng-model="form.product" required>
                          </select>
                        </div>
                        <div class="form-group">
                          <label class="form-label" for="qty">Quantity</label>
                          <input type="number" name="qty" class="form-control" ng-model="form.qty" min="1" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary mr-2 px-5" id="add_in_btn">Add</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

  <!-- End Add IN -->

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
<script src="{{ URL::asset('assets/js/select2.min.js') }}"></script>


<script type="text/javascript">
  $(document).ready(function(){

    $('#store').select2({
          ajax: {
              url: '/select-store-data',
              type: 'GET',
              dataType: 'json',
              data: function (params) {

                return {
                  search: params.term // search term
                };

              },
              processResults: function (response) {
                  return {
                    results: response
                  };
              },
              cache: true
          }
    });

    $('#product').select2({

    });
  });
</script>
<script type="text/javascript">
    (function () {
        var inApp = angular.module('inApp', ['angular.filter']);
        inApp.controller('InCtrl', function ($scope, $http, $sce, $compile) {

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
                            url: '/inventory-in-data',
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
                            {data: 'product', name: 'product.code', orderable: false, searchable: true},
                            {data: 'store', name: 'store.code', orderable: true, searchable: true},
                            {data: 'quantity', name: 'qty', orderable: true, searchable: true},
                            {data: 'date', name: 'created_at', orderable: true, searchable: false}
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

            vm.addIN = function(){

                $('#addINModal').appendTo('body').modal('show');

            };

            vm.populateProduct = function(){

              $("#product").empty();

              var store_id = vm.store;

              $http({
                    method: 'GET',
                    url: '/product-populate-data/'+store_id
                  
                  }).then(function successCallback(response) {

                      $('#product').select2({

                          data: response.data.data
                          
                      });
                    
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

            vm.submitAddIN = function () {
                event.preventDefault();

                $('#add_in_btn').prop('disabled', true);
                $('#add_in_btn').html('Saving... <i class="fa fa-spinner fa-spin"></i>');

                var product = $('#product').val();


                $http({
                    method: 'POST',
                    url: '/inventory-add-in',
                    data: JSON.stringify({
                        store_id: vm.store,
                        product_id: product,
                        qty: vm.qty
                    })
                 }).then(function successCallback(response) {

                    $('#add_in_btn').prop('disabled', false);
                    $('#add_in_btn').html('Add');

                    if (response.data.status == 'success'){

                          iziToast.success({
                              title: 'Success',
                              message: response.data.message,
                              position: 'topRight',
                          });

                          $('#store').html('<option selected hidden>Select Store</option>');
                          $("#product").empty();
                          $('input').val('');
                          $('#addINModal').appendTo("body").modal('hide');

                          getdata();

                    } else {

                        iziToast.error({
                            title: 'Error',
                            message: response.data.message,
                            position: 'topRight',
                        });

                    }

                }, function errorCallback(response) {

                    $('#add_in_btn').prop('disabled', false);
                    $('#add_in_btn').html('Add');

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