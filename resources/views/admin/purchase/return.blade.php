@extends('layouts.master')

@section('title', 'Return')

@section('header_scripts')
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
<audio id="audio" src="{{ URL::asset('assets/beep-07.wav') }}" autostart="false"></audio>	
<div class="main-content" style="min-height: 668px;" ng-app="returnpurchaseApp" ng-controller="ReturnPurchaseCtrl as form">
  	<section class="section">
	    <div class="section-header">
	      <h1>Return Purchase</h1>
	    </div>
	</section>

	<div class="section-body">
        <div class="col-12 col-md-12 col-lg-12">
          	<div class="card">
            	<div class="card-header">
              		<h4>Return Purchase</h4>
            	</div>
            	<div class="card-body">
            		<div class="row" style="padding:20px 0px 20px 0px;">
            			<div class="col-12 col-md-6 col-lg-6">
            				<label for="invoice_id" class="font-weight-bold">Invoice #:</label>
            				{{ $purchase->invoice_id }}
            			</div>
            			<div class="col-12 col-md-6 col-lg-6">
            				<label for="cashier" class="font-weight-bold">Cashier:</label>
            				{{ $purchase->user->name }}
            			</div>
            			<div class="col-12 col-md-6 col-lg-6">
            				<label for="invoice_id" class="font-weight-bold">Store:</label>
            				{{ $purchase->store->code.' - '.$purchase->store->name }}
            			</div>
            			<div class="col-12 col-md-6 col-lg-6">
            				@if(!empty($purchase->coupon_id))
            					<label for="cashier" class="font-weight-bold">Coupon Code:</label>
            					{{ $purchase->coupon->code }}
            				@endif
            			</div>
            		</div>

            		<div class="row">
            			<div class="col-12 col-md-12 col-lg-12">
            				<div class="table-responsive">
            					<table id="content-table" class="table table-md bg-ligh">
	            					<thead>
	            						<tr>
	            							<th>Product</th>
	            							<th>Quantity</th>
	            							<th>Price</th>
	            							<th><i class="fas fa-trash text-danger"></i></th>
	            						</tr>
	            					</thead>
                                    <tbody id="product_body">
    	            					@for($i = 0; $i < count($orders[0]['product_id']); $i++)
    	            						<tr id="removeThis_{{ $orders[0]['product_id'][$i] }}">
    	            							<td>{{ $orders[0]['product_name'][$i] }}</td>
    	            							<td>
                                                    <input type="number" name="qty[]" min="1" id="totalQty_{{ $orders[0]['product_id'][$i] }}" class="form-control sum_qty" ng-model="form.productQty{{ $orders[0]['product_id'][$i] }}" ng-init="form.productQty{{ $orders[0]['product_id'][$i] }}={{ $orders[0]['qty'][$i] }}" ng-enter="form.changeQty('{{ $orders[0]['product_id'][$i] }}')">
                                                </td>
    	            							<td>
                                                    <input type="text" name="price[]" onKeyDown="return false" id="totalPrice_{{ $orders[0]['product_id'][$i] }}" value="{{ $orders[0]['price'][$i] }}" class="form-control sum_price" style="border:none; background: #fff;" readonly>
                                                </td>
    	            							<td>
                                                    <span ng-click="form.removeProduct('{{ $orders[0]['product_id'][$i] }}')"><i class="text-danger fas fa-trash-alt"></i></span>
                                                </td>
    	            						</tr>
    	            					@endfor
                                    </tbody>
            					</table>
            				</div>
            			</div>
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="table-responsive">
                                <table id="content-table" class="table table-md bg-ligh">
                                    <thead>
                                        <tr>
                                            <th>Items</th>
                                            <th>Tax</th>
                                            <th>Discount Coupon</th>
                                            <th>Cash Payment</th>
                                            <th>Grand Total</th>
                                        </tr>
                                    </thead>
                                    <tr>    
                                        <td>
                                            <input type="text" name="grand_items" class="form-control" id="grand_items" value="{{ $purchase->grand_items }}" style="border:none; background-color: #fff;" onKeyDown="return false" readonly>
                                        </td>
                                        <td>
                                            <input type="text" name="grand_tax" class="form-control" id="grand_tax" value="{{ $purchase->grand_tax }}" style="border:none; background-color: #fff;" onKeyDown="return false" readonly>
                                        </td>
                                        @if(!empty($purchase->coupon_id))
                                            <td>
                                                <div class="row">
                                                    <div class="col-6 col-md-6 col-lg-6">

                                                        <input type="hidden" name="discount_min" class="form-control float-right" id="discount_min" value="{{ $purchase->coupon->minimum }}" style="border:none; background-color: #fff;" onKeyDown="return false" readonly>

                                                        <input type="text" name="discount_code" class="form-control float-right" id="discount_code" value="{{ $purchase->coupon->code }}" style="border:none; background-color: #fff;" onKeyDown="return false" readonly>
                                                    </div>
                                                    <div class="col-6 col-md-6 col-lg-6">
                                                        <input type="text" name="grand_discount" class="form-control float-right" id="grand_discount" value="{{ $purchase->coupon->discount }}" style="border:none; background-color: #fff;" onKeyDown="return false" readonly>
                                                    </div>
                                                </div>
                                            </td>
                                        @else
                                             <td>
                                                <div class="row">
                                                    <div class="col-6 col-md-6 col-lg-6">

                                                        <input type="hidden" name="discount_min" class="form-control float-right" id="discount_min" value="0.00" style="border:none; background-color: #fff;" onKeyDown="return false" readonly>


                                                        <input type="text" name="discount_code" class="form-control float-right" id="discount_code" value="#" style="border:none; background-color: #fff;" onKeyDown="return false" readonly>
                                                    </div>
                                                    <div class="col-6 col-md-6 col-lg-6">
                                                        <input type="text" name="grand_discount" class="form-control float-right" id="grand_discount" value="0.00" style="border:none; background-color: #fff;" onKeyDown="return false" readonly>
                                                    </div>
                                                </div>
                                            </td>
                                        @endif
                                        <td>
                                            <input type="text" name="cash_payment" class="form-control" id="cash_payment" value="{{ $purchase->cash_payment }}">
                                        </td>
                                        <td>
                                            <input type="text" name="grand_total" class="form-control" id="grand_total" value="{{ $purchase->grand_total }}" style="border:none; background-color: #fff;" onKeyDown="return false" readonly>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
            		</div>
            	</div>
            </div>
        </div>
    </div>
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

<script src="{{ URL::asset('assets/modules/izitoast/js/iziToast.min.js') }}"></script>

<script type="text/javascript">
(function () {

    var returnpurchaseApp = angular.module('returnpurchaseApp', ['angular.filter']);
        returnpurchaseApp.controller('ReturnPurchaseCtrl', function ($scope, $http, $sce, $compile) {

            var vm = this;

            vm.changeQty = function(product_id){
                var sound = document.getElementById("audio");
                sound.play();
            };

            vm.removeProduct = function(product_id){

                var minimum = parseFloat($('#discount_min').val());

                var total = parseInt($('#grand_total').val());

                if(total >= minimum){

                    var x = $('#removeThis_'+product_id).detach();

                    GrandTotalQty();
                    GrandTax();
                    GrandTotal();

                    if(checkDiscount(product_id) == 'MINNOTVALID'){

                        $('#product_body').prepend(x);

                    }

                    GrandTotalQty();
                    GrandTax();
                    GrandTotal();

                }else{

                    iziToast.error({
                        title: 'Error',
                        message: 'Must reach minimum purchase for coupon',
                        position: 'topRight',
                    });
                }

            };

            function checkDiscount(product_id){

                var minimum = $('#discount_min').val();

                var total = $('#grand_total').val();

                var status = 'MINVALID';

                if(total >= minimum){

                    var status = 'MINNOTVALID';

                }

                console.log(total+' '+status+' '+minimum);
                return status;
            }
            function GrandTotalQty(){

                var grand_qty = 0;

                $('.sum_qty').each(function (index, element) {
                  grand_qty = grand_qty + parseFloat($(element).val());
                });

                $('#grand_items').val(grand_qty);

            }

            function GrandTax(){

                var grand_tax = 0;

                var grand_tax = $('#grand_total').val() * .10;

                $('#grand_tax').val(parseFloat(grand_tax));

            }

            function GrandTotal(){

                var grand_total = 0;

                var grand_discount = parseFloat($('#grand_discount').val());

                $('.sum_price').each(function (index, element) {
                    grand_total = grand_total + parseFloat($(element).val());
                });

                var total = grand_total - grand_discount;

                $('#grand_total').val(total);

            }
    });

    returnpurchaseApp.directive('ngEnter', function () {

        var currentBoxNumber = 0;

        return function (scope, element, attrs) {
            element.bind("keydown keypress", function (event) {
                if (event.which === 13) {
                    scope.$apply(function () {
                      scope.$eval(attrs.ngEnter);
                    });

                    event.preventDefault();
                }

                if (event.keyCode == 13) {
                    textboxes = $("input.qty");
                    currentBoxNumber = textboxes.index(this);
                    if (textboxes[currentBoxNumber + 1] != null) {
                        nextBox = textboxes[currentBoxNumber + 1];
                        nextBox.focus();
                    }
                    event.preventDefault();
                    return false;
                }
            });
        };
    });

})();
</script>
@stop
