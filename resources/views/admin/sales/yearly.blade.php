@extends('layouts.master')

@section('title', 'Yearly Sales')

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

<div class="main-content" style="min-height: 668px;" ng-app="yearlySalesApp" ng-controller="YearlySalesCtrl as form">
    <section class="section">
        <div class="section-header">
          <h1>Yearly Sales</h1>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>Yearly Sales</h4>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                          <a class="nav-link active show font-weight-bold" id="graph-tab" data-toggle="tab" href="#graph" role="tab" aria-controls="home" aria-selected="true">Graph</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link font-weight-bold" id="table-tab" data-toggle="tab" href="#table" role="tab" aria-controls="profile" aria-selected="false">Table</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade active show" id="graph" role="tabpanel" aria-labelledby="graph-tab">
                            {!! $yearlyChart->container() !!}
                        </div>
                        <div class="tab-pane fade" id="table" role="tabpanel" aria-labelledby="table-tab">
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
                                        <th>Purchases</th>
                                        <th>Total</th>
                                        <th>Date</th>
                                      </tr>
                                  </thead>  
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection

@section('footer_scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>
{!! $yearlyChart->script() !!}

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
        var yearlySalesApp = angular.module('yearlySalesApp', ['angular.filter']);
        yearlySalesApp.controller('YearlySalesCtrl', function ($scope, $http, $sce, $compile) {

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
                            url: '/sales-yearly-data',
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
                            {data: 'purchases', name: 'orders', orderable: false, searchable: false},
                            {data: 'total', name: ' grand_total', orderable: false, searchable: false},
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
      });

  })();
</script>
@stop