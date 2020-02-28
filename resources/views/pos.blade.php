@extends('layouts.pos')

@section('title', 'POS')

@section('header_scripts')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/modules/izitoast/css/iziToast.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ URL::asset('css/keyboard.css') }}">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<style>
    .table-responsive {
        font-size:15px!important;
    }
    .wrapper{
      padding: 20px;
    }
    .article .article-header {
      height: 90px;
    }
    .scroll-div{
      height: 600px;
      overflow: scroll;
      overflow-x: hidden;
    }

    .table-scroll{
      height: 300px;
      overflow: scroll;
      overflow-x: hidden;
    }
    ::-webkit-scrollbar {
        width: 0px;  /* Remove scrollbar space */
        background: transparent;  /* Optional: just make scrollbar invisible */
    }
    /* Optional: show position indicator in red */
    ::-webkit-scrollbar-thumb {
        background: #FF0000;
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
<div class="wrapper" ng-app="posApp" ng-controller="PosCtrl as form">
  <div class="row">

    <div class="col-12 col-md-7 col-lg-7">
      <div class="card">
        <div class="card-header">
          <div class="col-12 col-md-6 col-lg-6">
            <div class="row">
              <div class="col-12 col-md-4 col-lg-4">
                  <p class="font-weight-bold"> Pos #:</p>
              </div>
              <div class="col-12 col-md-8 col-lg-8">
                  <p> 1</p>
              </div>
              <div class="col-12 col-md-4 col-lg-4">
                  <p class="font-weight-bold"> Store:</p>
              </div>
              <div class="col-12 col-md-8 col-lg-8">
                  <p>{{ $user->store->code }} - {{ $user->store->name }}</p>
              </div>
              <div class="col-12 col-md-4 col-lg-4">
                  <p class="font-weight-bold"> Name:</p>
              </div>
              <div class="col-12 col-md-8 col-lg-8">
                  <p>{{ $user->name }}</p>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-6 col-lg-6">
            <label for="product">Scan / Input Product</label>
            <input type="text" name="product" id="product_input" placeholder="Product Code" class="form-control use-keyboard-input" ng-model="form.addProduct" ng-enter="form.submitAddProduct()"style="border-radius: none;">
          </div>
          {{-- <div class="col-12 col-md-6 col-lg-6 bg-primary" style="height:100px;padding: 20px;">
              <h4 class="text-white">Items</h4>
              <p class="lead text-white float-right">10</p>
          </div>
          <div class="col-12 col-md-6 col-lg-6 bg-success" style="height:100px;padding: 20px;">
              <h4 class="text-white">Price</h4>
              <p class="lead text-white float-right">100,000</p>
          </div> --}}
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
              <div class="table-scroll table-responsive">
                <table class="table">
                  <tbody>
                    <tr class="bg-light">
                      <td class="font-weight-bold">Product</td>
                      <td class="font-weight-bold">Quantity</td>
                      <td class="font-weight-bold">Price</td>
                      <td><i class="text-danger fas fa-trash-alt"></i></td>
                    </tr>
                  </tbody>
                  <tbody id="add_product">
                  </tbody>
                </table>
              </div>
            </div>
            <div class="col-12 col-md-12 col-lg-12">
              <div class="table-responsive">
                <table class="table">
                  <tbody>
                    <tr class="bg-light">
                      <td class="font-weight-bold">Items</td>
                      <td class="font-weight-bold">Tax</td>
                      <td class="font-weight-bold">Discount Coupon</td>
                      <td class="font-weight-bold">Grand Total</td>
                    </tr>
                    <tr>
                      <tr>
                        <td>
                          <input type="text" name="grand_items" class="form-control" id="grand_items" value="0" style="border:none; background-color: #fff;" onKeyDown="return false" readonly>
                        </td>
                        <td>
                          <input type="text" name="grand_tax" class="form-control" id="grand_tax" value="0" style="border:none; background-color: #fff;" onKeyDown="return false" readonly>
                        </td>
                        <td>
                          <div class="row">
                            <div class="col-6 col-md-6 col-lg-6">
                              <input type="hidden" name="coupon_id" class="form-control" id="coupon_id" style="border:none; background-color: #fff;" onKeyDown="return false">
                              <input type="text" name="discount_code" class="form-control float-right" id="discount_code" value="#" style="border:none; background-color: #fff;" onKeyDown="return false" readonly>
                            </div>
                            <div class="col-6 col-md-6 col-lg-6">
                              <input type="text" name="grand_discount" class="form-control float-right" id="grand_discount" value="0.00" style="border:none; background-color: #fff;" onKeyDown="return false" readonly>
                            </div>
                          </div>
                        </td>
                        <td><input type="text" name="grand_total" class="form-control" id="grand_total" value="0.00" style="border:none; background-color: #fff;" onKeyDown="return false" readonly></td>
                      </tr>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="col-12 col-md-12 col-lg-12">
              <div class="row">
                <div class="col-12 col-md-4 col-lg-4">
                  <p class="bg-primary text-white text-center font-weight-bold" style="padding:10px; cursor: pointer;"><i class=" fa fa-credit-card"></i> Card</p>
                </div>
                <div class="col-12 col-md-4 col-lg-4">
                    <p class="bg-primary text-white text-center font-weight-bold" style="padding:10px; cursor: pointer;" ng-click="form.cashPayment()"><i class="fa fa-money-bill"></i> Cash</p>
                </div>
                <div class="col-12 col-md-4 col-lg-4">
                    <p class="bg-primary text-white text-center font-weight-bold" style="padding:10px; cursor: pointer;" ng-click="form.giftCard()"><i class="fa fa-money-check"></i> Coupon</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-5 col-lg-5">
      <div class="row">
        <div class="col-12 col-md-4 col-lg-3" ng-click="form.searchAll()" style="cursor:pointer;">
          <p class="bg-primary text-white text-center" style="padding:10px;">All Products</p>
        </div>
        <div class="col-12 col-md-4 col-lg-3" ng-click="form.openBrand()" style="cursor:pointer;">
          <p class="bg-primary text-white text-center" style="padding:10px;">Brand</p>
        </div>
        <div class="col-12 col-md-4 col-lg-3" ng-click="form.openCategory()" style="cursor:pointer;">
          <p class="bg-primary text-white text-center" style="padding:10px;">Category</p>
        </div>
        <div class="col-12 col-md-4 col-lg-3" ng-click="form.openPurchases()" style="cursor:pointer;">
          <p class="bg-primary text-white text-center" style="padding:10px;">Purchases</p>
        </div>
      </div>
      <div class="card">
        <div class="card-body">
          <div class="row scroll-div">
            <!--- PRODUCT TABLE --->
            <div class="col-12 col-md-3 col-lg-3" id="products-card" style="cursor:pointer;display: none;" ng-repeat="product in products">
              <article class="article article-style-b" ng-click="form.addToCartProduct(product.id, product.name, product.code, product.qty, product.cost, product.price, product.tax)">
                <div class="article-header">
                  <div class="article-image" data-background="assets/static/images/products/@{{ product.image }}" style="background-image: url(assets/static/images/products/@{{ product.image }});">
                  </div>
                </div>
              </article>
              <p class="text-center">@{{ product.name }}</p>
            </div>
            <!--- RETURN TABLE --->
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
  </div>

  <!-- Search Brand -->

  <div class="modal fade" id="searchBrandModal" tabindex="-1" role="dialog" aria-labelledby="searchBrandModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="searchBrandModalLabel">Search Brand</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
          <div class="modal-body text-center">
            <div class="row">
               @foreach($brands as $brand)
                  <div class="col-12 col-md-4 col-lg-4" ng-click="form.searchBrand('{{ $brand->id }}')" style="cursor:pointer;">
                      <p class="bg-primary text-white text-center" style="padding:10px;">{{ $brand->name }}</p>
                  </div>
               @endforeach
            </div>
          </div>
      </div>
    </div>
  </div>

  <!-- End Search Brand -->

  <!-- Search Category -->

  <div class="modal fade" id="searchCategoryModal" tabindex="-1" role="dialog" aria-labelledby="searchCategoryModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="searchCategoryModalLabel">Search Category</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
          <div class="modal-body text-center">
            <div class="row">
               @foreach($categories as $category)
                  <div class="col-12 col-md-4 col-lg-4" ng-click="form.searchCategory('{{ $category->id }}')" style="cursor:pointer;">
                      <p class="bg-primary text-white text-center" style="padding:10px;">{{ $category->name }}</p>
                  </div>
               @endforeach
            </div>
          </div>
      </div>
    </div>
  </div>

  <!-- End Search Categorya -->

  <!-- Cash Payment -->

  <div class="modal fade" id="cashPaymentModal" tabindex="-1" role="dialog" aria-labelledby="cashPaymentModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="cashPaymentModalLabel">Cash Payment</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form class="form-box ng-pristine ng-invalid ng-invalid-required" name="cashPaymentForm" ng-submit="form.submitCashPayment(cashPaymentForm.$valid)">
            <div class="form-group">
              <label for="cash_payment">Cash Payment</label>
              <input type="number" name="cash_payment" id="cash_payment" class="form-control" placeholder="Cash Payment">
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-primary float-right" id="submit_cash_payment_btn">Submit Payment</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  
  <!-- End Cash Payment -->

  <!-- Gift Card -->

  <div class="modal fade" id="giftCardModal" tabindex="-1" role="dialog" aria-labelledby="giftCardModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="giftCardModalLabel">Coupon Code</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form class="form-box ng-pristine ng-invalid ng-invalid-required" name="giftCardForm" ng-submit="form.submitGiftCard(giftCardForm.$valid)">
            <div class="form-group">
              <label for="gift_card_code">Coupon Code</label>
              <input type="text" name="gift_card_code" ng-model="form.gift_card_code" class="form-control" placeholder="Coupon Code">
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-primary float-right" id="submit_gift_card_btn">Submit Discount</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  
  <!-- End Gift Card  -->

  <!-- Invoice Purchase -->

    <div class="modal fade" id="invoicePurchaseModal" tabindex="-1" role="dialog" aria-labelledby="invoicePurchaseModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
          <div class="modal-content invoice-purchase">

          </div>
        </div>
    </div>

  <!-- End Invoice Purchase -->

</div>

@endsection

@section('footer_scripts')

<script src="{{ URL::asset('js/keyboard.js') }}"></script>
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

      const Keyboard = {
        elements: {
            main: null,
            keysContainer: null,
            keys: []
        },

        eventHandlers: {
            oninput: null,
            onclose: null
        },

        properties: {
            value: "",
            capsLock: false
        },

        init() {
            // Create main elements
            this.elements.main = document.createElement("div");
            this.elements.keysContainer = document.createElement("div");

            // Setup main elements
            this.elements.main.classList.add("keyboard", "keyboard--hidden");
            this.elements.keysContainer.classList.add("keyboard__keys");
            this.elements.keysContainer.appendChild(this._createKeys());

            this.elements.keys = this.elements.keysContainer.querySelectorAll(".keyboard__key");

            // Add to DOM
            this.elements.main.appendChild(this.elements.keysContainer);
            document.body.appendChild(this.elements.main);

            // Automatically use keyboard for elements with .use-keyboard-input
            document.querySelectorAll(".use-keyboard-input").forEach(element => {
                element.addEventListener("focus", () => {
                    this.open(element.value, currentValue => {
                        element.value = currentValue;
                    });
                });
            });
        },

        _createKeys() {
            const fragment = document.createDocumentFragment();
            const keyLayout = [
                "close",
                "1", "2", "3", "4", "5", "6", "7", "8", "9", "0", "backspace", 
                "q", "w", "e", "r", "t", "y", "u", "i", "o", "p",
                "caps", "a", "s", "d", "f", "g", "h", "j", "k", "l", "enter",
                "done", "z", "x", "c", "v", "b", "n", "m", ",", ".", "?",
                "space"
            ];

            // Creates HTML for an icon
            const createIconHTML = (icon_name) => {
                return `<i class="material-icons">${icon_name}</i>`;
            };

            keyLayout.forEach(key => {
                const keyElement = document.createElement("button");
                const insertLineBreak = ["close", "backspace", "p", "enter", "?"].indexOf(key) !== -1;

                // Add attributes/classes
                keyElement.setAttribute("type", "button");
                keyElement.classList.add("keyboard__key");

                switch (key) {
                    case "close":
                          keyElement.classList.add("keyboard__key--wide");
                          keyElement.innerHTML = createIconHTML("close");

                          keyElement.addEventListener("click", () => {
                            this.close();
                            this._triggerEvent("onclose");
                          });
                          break;

                    case "backspace":
                        keyElement.classList.add("keyboard__key--wide");
                        keyElement.innerHTML = createIconHTML("backspace");

                        keyElement.addEventListener("click", () => {
                            this.properties.value = this.properties.value.substring(0, this.properties.value.length - 1);
                            this._triggerEvent("oninput");
                        });

                        break;

                    case "caps":
                        keyElement.classList.add("keyboard__key--wide", "keyboard__key--activatable");
                        keyElement.innerHTML = createIconHTML("keyboard_capslock");

                        keyElement.addEventListener("click", () => {
                            this._toggleCapsLock();
                            keyElement.classList.toggle("keyboard__key--active", this.properties.capsLock);
                        });

                        break;

                    case "enter":
                        keyElement.classList.add("keyboard__key--wide");
                        keyElement.innerHTML = createIconHTML("keyboard_return");

                        keyElement.addEventListener("click", () => {
                            this.properties.value += "\n";
                            this._triggerEvent("oninput");
                        });

                        break;

                    case "space":
                        keyElement.classList.add("keyboard__key--extra-wide");
                        keyElement.innerHTML = createIconHTML("space_bar");

                        keyElement.addEventListener("click", () => {
                            this.properties.value += " ";
                            this._triggerEvent("oninput");
                        });

                        break;

                    case "done":
                        keyElement.classList.add("keyboard__key--wide", "keyboard__key--dark");
                        keyElement.innerHTML = createIconHTML("check_circle");

                        keyElement.addEventListener("click", () => {
                            this.close();
                            this._triggerEvent("onclose");

                            const product_code = $('#product_input').val();
                            
                            addProductInput(product_code);
                        });

                        break;

                    default:
                        keyElement.textContent = key.toLowerCase();

                        keyElement.addEventListener("click", () => {
                            this.properties.value += this.properties.capsLock ? key.toUpperCase() : key.toLowerCase();
                            this._triggerEvent("oninput");
                        });

                        break;
                }

                fragment.appendChild(keyElement);

                if (insertLineBreak) {
                    fragment.appendChild(document.createElement("br"));
                }
            });

            return fragment;
        },

        _triggerEvent(handlerName) {
            if (typeof this.eventHandlers[handlerName] == "function") {
                this.eventHandlers[handlerName](this.properties.value);
            }
        },

        _toggleCapsLock() {
            this.properties.capsLock = !this.properties.capsLock;

            for (const key of this.elements.keys) {
                if (key.childElementCount === 0) {
                    key.textContent = this.properties.capsLock ? key.textContent.toUpperCase() : key.textContent.toLowerCase();
                }
            }
        },

        open(initialValue, oninput, onclose) {
            this.properties.value = initialValue || "";
            this.eventHandlers.oninput = oninput;
            this.eventHandlers.onclose = onclose;
            this.elements.main.classList.remove("keyboard--hidden");
        },

        close() {
            this.properties.value = "";
            this.eventHandlers.oninput = oninput;
            this.eventHandlers.onclose = onclose;
            this.elements.main.classList.add("keyboard--hidden");
        }
    };

    window.addEventListener("DOMContentLoaded", function () {
        Keyboard.init();
    });

    function addProductInput(product_code){

        var sound = document.getElementById("audio");
            sound.play();

        iziToast.success({
            title: 'Success',
            message: product_code,
            position: 'topRight',
        });
    }

    var posApp = angular.module('posApp', ['angular.filter']);
    posApp.controller('PosCtrl', function ($scope, $http, $sce, $compile) {

        var vm = this;

        $scope.brand_id = 'search_brand=all';
        $scope.category_id = '&search_category=all';

        vm.openBrand = function(){
            $('#searchBrandModal').appendTo('body').modal('show');
        };

        vm.searchBrand = function (brand_id) {

            $('#searchBrandModal').appendTo('body').modal('hide');
            $scope.brand_id = 'search_brand=' + brand_id;
            getdata();
        };

        vm.openCategory = function(){
            $('#searchCategoryModal').appendTo('body').modal('show');
        };

        vm.searchCategory = function (category_id) {

            $('#searchCategoryModal').appendTo('body').modal('hide');
            $scope.category_id = '&search_category=' + category_id;
            getdata();
        };

        vm.searchAll = function () {

            $scope.brand_id = 'search_brand=all';
            $scope.category_id = '&search_category=all';
            getdata();
        };

        vm.openPurchases = function(){

        };
        
        getdata();
        function getdata() {

          $http.get('/pos-product-data?'+ $scope.brand_id + $scope.category_id).success(function (response) {
              console.log(response.products);
              $scope.products = response.products;
          });

        }

        vm.submitAddProduct = function(){

          var sound = document.getElementById("audio");
              sound.play();
              
              alert(vm.addProduct);
        };

        vm.addToCartProduct = function(product_id, name, code, qty, cost, price, tax){
            var sound = document.getElementById("audio");
            sound.play();

            $(".table-scroll").animate({ scrollTop: 20000000 }, "slow");

            var id_exists = $("input[name='product_id[]']").map(function(){return $(this).val();}).get();

            if(checkValue(product_id, id_exists) == "Exist"){

              if(parseInt($('#totalQty_'+product_id).val()) < qty){

                var plus_qty = parseInt($('#totalQty_'+product_id).val()) + 1;

                $('#totalQty_'+product_id).val(parseInt(plus_qty));

                var total_price = parseFloat(plus_qty * price);

                $('#totalPrice_'+product_id).val(parseFloat(total_price).toFixed(2));

                GrandTotalQty();
                GrandTax();
                GrandTotal();

              }else{

                iziToast.warning({
                    title: 'Warning',
                    message: 'Reaches maximum quantity',
                    position: 'topRight',
                });

              }

            }else{

               $('#add_product').append($compile('<tr id="removeThis_'+product_id+'">'

                                        +'<td>'
                                            +name
                                            +'<input type="hidden" name="name[]" value="'+ name +'" class="form-control">'

                                            +'<input type="hidden" name="product_id[]" value="'+ product_id +'" class="form-control">'
                                        +'</td>'

                                        +'<td>'
                                            +'<input type="text" name="qty[]" min="1" onKeyDown="return false" id="totalQty_'+product_id+'" value="1" class="form-control sum_qty" style="border:none; background: #fff;" readonly>'
                                        +'</td>'

                                        +'<td>'
                                            +'<input type="text" name="price[]" onKeyDown="return false" id="totalPrice_'+product_id+'" value="'+parseFloat(price).toFixed(2)+'" class="form-control sum_price" style="border:none; background: #fff;" readonly>'
                                        +'</td>'

                                        +'<td>'
                                            +'<span ng-click="form.removeProduct('+product_id+')"><i class="text-danger fas fa-trash-alt"></i></span>'
                                        +'</td>'

                                        +'</tr>')($scope));
              GrandTotalQty();
              GrandTax();
              GrandTotal();

              iziToast.success({
                  title: 'Success',
                  message: 'Product Added',
                  position: 'topRight',
              });

            }
           

            
        };

        function checkValue(value, arr){

          var status = 'Not exist';
         
          for(var i=0; i<arr.length; i++){

            var name = arr[i];

            if(name == value){

              status = 'Exist';

              break;
            }

          }

          return status;

        }

        vm.removeProduct = function(product_id){

          $('#removeThis_'+product_id).remove();

          GrandTotalQty();
          GrandTax();
          GrandTotal();

        };

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

          $('#grand_tax').val(parseFloat(grand_tax).toFixed(2));

        }

        function GrandTotal(){

          var grand_total = 0;

          var grand_discount = parseFloat($('#grand_discount').val());

          $('.sum_price').each(function (index, element) {
                grand_total = grand_total + parseFloat($(element).val());
          });

          var total = grand_total - grand_discount;

          $('#grand_total').val(total.toFixed(2));

        }

        vm.cashPayment = function(){
          $('#cashPaymentModal').appendTo('body').modal('show');
        };

        vm.submitCashPayment = function(){
          
          var product_id = $("input[name='product_id[]']").map(function(){return $(this).val();}).get();
          var product_name = $("input[name='name[]']").map(function(){return $(this).val();}).get();
          var qty = $("input[name='qty[]']").map(function(){return $(this).val();}).get();
          var price = $("input[name='price[]']").map(function(){return $(this).val();}).get();
          var grand_items = $('#grand_items').val();
          var coupon_id = $('#coupon_id').val();
          var grand_total = $('#grand_total').val();
          var grand_tax = $('#grand_tax').val();
          var cash_payment = $('#cash_payment').val();

          $http({
              method: 'POST',
              url: '/add-pos-purchase',
              data: JSON.stringify({
                  product_id: product_id,
                  product_name: product_name,
                  qty: qty,
                  price: price,
                  grand_items: grand_items,
                  coupon_id: coupon_id,
                  grand_total: grand_total,
                  grand_tax: grand_tax,
                  cash_payment: cash_payment
              })
          }).then(function successCallback(response) {

              $('#submit_cash_payment_btn').prop('disabled', false);
              $('#submit_cash_payment_btn').html('Submit Payment');

              if (response.data.status == 'success'){

                  $('#cashPaymentModal').appendTo('body').modal('hide');

                  $('#cash_payment').val('');

                  iziToast.success({
                      title: 'Success',
                      message: response.data.message,
                      position: 'topRight',
                  });

                  $('#invoicePurchaseModal').appendTo("body").modal('show');
                  $('.invoice-purchase').show().html($compile(response.data.responeHtml)($scope));

              }else{

                  iziToast.error({
                      title: 'Error',
                      message: response.data.message,
                      position: 'topRight',
                  }); 
              }

          }, function errorCallback(response) {

              $('#submit_cash_payment_btn').prop('disabled', false);
              $('#submit_cash_payment_btn').html('Submit Payment');

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

        vm.giftCard = function(){
          $('#giftCardModal').appendTo('body').modal('show');
        };

        vm.submitGiftCard = function(){
          
          $('#submit_gift_card_btn').prop('disabled', true);
          $('#submit_gift_card_btn').html('Checking... <i class="fa fa-spinner fa-spin"></i>');

          var grand_total = $('#grand_total').val();

          var giftCard = vm.gift_card_code;

          $http({
              method: 'POST',
              url: '/add-gift-card/'+giftCard,
              data: JSON.stringify({
                  grand_total: grand_total
              })
           }).then(function successCallback(response) {

              $('#submit_gift_card_btn').prop('disabled', false);
              $('#submit_gift_card_btn').html('Submit Discount');

              if (response.data.status == 'success'){

                    iziToast.success({
                        title: 'Success',
                        message: response.data.message,
                        position: 'topRight',
                    });

                    $('#coupon_id').val(response.data.coupon.id);
                    $('#discount_code').val(response.data.coupon.code);
                    $('#grand_discount').val(parseFloat(response.data.coupon.discount).toFixed(2));

                    vm.gift_card_code = '';
                    $('#giftCardModal').appendTo("body").modal('hide');

                    GrandTotal();

              } else {

                  vm.gift_card_code = '';

                  iziToast.error({
                      title: 'Error',
                      message: response.data.message,
                      position: 'topRight',
                  });

              }

          }, function errorCallback(response) {

              $('#submit_gift_card_btn').prop('disabled', false);
              $('#submit_gift_card_btn').html('Submit Discount');

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

    posApp.directive('ngEnter', function () {
        return function (scope, element, attrs) {
            element.bind("keydown keypress", function (event) {
                if (event.which === 13) {
                    scope.$apply(function () {
                      scope.$eval(attrs.ngEnter);
                    });

                    event.preventDefault();
                }
            });
        };
    });

})();
</script>
@stop