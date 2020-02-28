@extends('layouts.master')

@section('title', 'Dashboard')

@section('header_scripts')

@stop

@section('content')

<!-- Main Content -->
<div class="main-content" style="min-height: 668px;" ng-app="dashboardApp" ng-controller="DashboardCtrl as form"> 
  <section class="section">
    <div class="row">
      <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="card card-statistic-2">
          <div class="card-stats">
            <div class="card-stats-title">User Statistics
            </div>
            <div class="card-stats-items">
              <div class="card-stats-item">
                <div class="card-stats-item-count">
                  <span ng-bind="data.summary.admin_count">0</span>
                </div>
                <div class="card-stats-item-label">Admin</div>
              </div>
              <div class="card-stats-item">
                <div class="card-stats-item-count">
                  <span ng-bind="data.summary.employee_count">0</span>
                </div>
                <div class="card-stats-item-label">Employee</div>
              </div>
              <div class="card-stats-item">
                <div class="card-stats-item-count">
                  <span ng-bind="data.summary.seller_count">0</span>
                </div>
                <div class="card-stats-item-label">Seller</div>
              </div>
              <div class="card-stats-item">
                <div class="card-stats-item-count">
                  <span ng-bind="data.summary.user_count">0</span>
                </div>
                <div class="card-stats-item-label">User</div>
              </div>
            </div>
          </div>
          <div class="card-icon shadow-primary bg-primary">
            <i class="fas fa-users"></i>
          </div>
          <div class="card-wrap">
            <div class="card-header">
              <h4>Total User</h4>
            </div>
            <div class="card-body">
              <span ng-bind="data.summary.total_users">0</span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="card card-statistic-2">
          <div class="card-stats">
            <div class="card-stats-title">Sale Statistics </div>
            <div class="card-stats-items">
              <div class="card-stats-item">
                <div class="card-stats-item-count">
                  <span ng-bind="data.summary.daily_sale | number:0">0</span>
                </div>
                <div class="card-stats-item-label">Daily</div>
              </div>
              <div class="card-stats-item">
                <div class="card-stats-item-count">
                  <span ng-bind="data.summary.weekly_sale | number:0">0</span>
                </div>
                <div class="card-stats-item-label">Weekly</div>
              </div>
              <div class="card-stats-item">
                <div class="card-stats-item-count">
                  <span ng-bind="data.summary.monthly_sale | number:0">0</span>
                </div>
                <div class="card-stats-item-label">Montly</div>
              </div>
              <div class="card-stats-item">
                <div class="card-stats-item-count">
                  <span ng-bind="data.summary.yearly_sale | number:0">0</span>
                </div>
                <div class="card-stats-item-label">Yearly</div>
              </div>
            </div>
          </div>
          <div class="card-icon shadow-primary bg-primary">
            <i class="fas fa-dollar-sign"></i>
          </div>
          <div class="card-wrap">
            <div class="card-header">
              <h4>Total Sales</h4>
            </div>
            <div class="card-body">
              &#8369; <span ng-bind="data.summary.total_sales | number:0">0</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="card">
          {!! $Monthlychart->container() !!}
        </div>
      </div>
      <div class="col-md-6">
        <div class="card">
           {!! $Yearlychart->container() !!}
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            <h4>Invoices</h4>
            <div class="card-header-action">
              <a href="{{ url('/purchase/list') }}">View More <i class="fas fa-chevron-right"></i></a>
            </div>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive table-invoice">
              <table class="table table-striped">
                <tbody>
                  <tr>
                    <th>Invoice ID</th>
                    <th>Cashier Name</th>
                    <th>Store</th>
                    <th>Date</th>
                  </tr>
                @if(count($purchases) > 0)
                  @foreach($purchases as $purchase)
                    <tr>
                      <td><a href="#">{{ $purchase->invoice_id }}</a></td>
                      <td class="font-weight-600">{{ $purchase->user->username }}</td>
                      <td>{{ $purchase->store->code }}</td>
                      <td>{{ date('M d Y', strtotime($purchase->created_at)).' | '.$purchase->created_at->diffForHumans() }}</td>
                    </tr>
                  @endforeach
                @else
                  <tr>
                    <td colspan="4">
                      <span class="alert alert-danger text-center">No invoice data</span>
                    </td>
                  </tr>
                @endif
              </tbody></table>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card card-hero">
          <div class="card-header">
            <div class="card-icon">
              <i class="far fa-question-circle"></i>
            </div>
            <h4>14</h4>
            <div class="card-description">Customers need help</div>
          </div>
          <div class="card-body p-0">
            <div class="tickets-list">
              <a href="#" class="ticket-item">
                <div class="ticket-title">
                  <h4>My order hasn't arrived yet</h4>
                </div>
                <div class="ticket-info">
                  <div>Laila Tazkiah</div>
                  <div class="bullet"></div>
                  <div class="text-primary">1 min ago</div>
                </div>
              </a>
              <a href="#" class="ticket-item">
                <div class="ticket-title">
                  <h4>Please cancel my order</h4>
                </div>
                <div class="ticket-info">
                  <div>Rizal Fakhri</div>
                  <div class="bullet"></div>
                  <div>2 hours ago</div>
                </div>
              </a>
              <a href="#" class="ticket-item">
                <div class="ticket-title">
                  <h4>Do you see my mother?</h4>
                </div>
                <div class="ticket-info">
                  <div>Syahdan Ubaidillah</div>
                  <div class="bullet"></div>
                  <div>6 hours ago</div>
                </div>
              </a>
              <a href="features-tickets.html" class="ticket-item ticket-more">
                View All <i class="fas fa-chevron-right"></i>
              </a>
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

{!! $Monthlychart->script() !!}

{!! $Yearlychart->script() !!}

<script src="{{ URL::asset('assets/plugins/angular/angular.min.js')  }}"></script>
<script src="{{ URL::asset('assets/plugins/angular/angular.filter.min.js')  }}"></script>
<script src="{{ URL::asset('assets/plugins/angular/angular-animate.min.js')  }}"></script>
<script src="{{ URL::asset('assets/plugins/angular/angular-aria.min.js')  }}"></script>
<script src="{{ URL::asset('assets/plugins/angular/angular-messages.min.js')  }}"></script>
<script src="{{ URL::asset('assets/plugins/angular/angular-material.min.js')  }}"></script>
<script src="{{ URL::asset('assets/plugins/angular/angular-sanitize.js')  }}"></script>

<script type="text/javascript">
(function () {

    var dashboardApp = angular.module('dashboardApp', ['angular.filter']);
    dashboardApp.controller('DashboardCtrl', function ($scope, $http, $sce) {

        var vm = this;

        getdata();
        
        function getdata() {
            $http.get('/admin-dashboard-data').success(function (data) {
                $scope.data = data;
            });
        }

    });

})();
</script>

@stop