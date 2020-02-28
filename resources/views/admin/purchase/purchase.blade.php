@extends('layouts.master')

@section('title', 'Purchase')

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

<div class="main-content" style="min-height: 668px;" ng-app="PurchaseApp" ng-controller="PurchaseCtrl as form">
  <section class="section">
    <div class="section-header">
      <h1>Purchases</h1>
    </div>

    <div class="section-body">
        <div class="col-12 col-md-12 col-lg-12">
          <div class="card">
            <div class="card-header">
              <h4>Purchases</h4>
              
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
                        <th>Invoice ID</th>
                        <th>Cashier</th>
                        <th>Store</th>
                        <th>Coupon</th>
                        <th>Purchases</th>
                        <th># of Item</th>
                        <th>Cash</th>
                        <th>Tax</th>
                        <th>Total</th>
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

  <!-- Invoice Purchase -->

    <div class="modal fade" id="invoicePurchaseModal" tabindex="-1" role="dialog" aria-labelledby="invoicePurchaseModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content invoice-purchase">

          </div>
        </div>
    </div>

  <!-- End Invoice Purchase -->
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
        var PurchaseApp = angular.module('PurchaseApp', ['angular.filter']);
        PurchaseApp.controller('PurchaseCtrl', function ($scope, $http, $sce, $compile) {

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
                            url: '/purchase-list-data',
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
                            {data: 'invoice', name: 'invoice_id', orderable: false, searchable: true},
                            {data: 'cashier', name: 'user_id', orderable: false, searchable: false},
                            {data: 'store', name: 'store_id', orderable: false, searchable: false},
                            {data: 'coupon', name: 'coupon_id', orderable: false, searchable: false},
                            {data: 'purchases', name: 'orders', orderable: false, searchable: false},
                            {data: 'items', name: ' grand_items', orderable: false, searchable: false},
                            {data: 'cash', name: 'cash_payment', orderable: false, searchable: false},
                            {data: 'tax', name: ' grand_tax', orderable: false, searchable: false},
                            {data: 'total', name: ' grand_total', orderable: false, searchable: false},
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

            vm.showPurchasess = function(purchase_id){
                
                $http({
                  method: 'GET',
                  url: '/show-purchases/'+purchase_id
                }).then(function successCallback(response) {

                    if (response.data.status == 'success'){

                      $('#invoicePurchaseModal').appendTo("body").modal('show');
                      $('.invoice-purchase').show().html($compile(response.data.responeHtml)($scope));

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
      });

    })();
</script>
@stop