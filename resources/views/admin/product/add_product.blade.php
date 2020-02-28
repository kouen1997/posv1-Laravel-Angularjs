@extends('layouts.master')

@section('title', 'Add Product')

@section('header_scripts')
<style type="text/css">
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

<div class="main-content" style="min-height: 668px;" ng-app="productApp" ng-controller="productCtrl as form">
  <section class="section">
    <div class="section-header">
      <h1>Add Product</h1>
    </div>

    <div class="section-body">
        <div class="col-12 col-md-12 col-lg-12">
          <div class="card">
            <div class="card-header">
              <h4>Add Product</h4>
            </div>
            <div class="card-body">
              @if($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
              @endif
              <form action="{{ url('/product/addproduct') }}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label class="form-label" for="image">Procuct Image</label>
                      <input class="form-control" name="image" type="file" value="{{ old('image') }}"/>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label class="form-label" for="name">Product Name</label>
                      <input class="form-control" name="name" type="text" value="{{ old('name') }}"/>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="form-label" for="type">Product Type</label>
                      <select class="form-control" name="type" value="{{ old('type') }}"/>
                        <option selected disabled>Choose Product Type</option>
                        <option value="standard">Standard</option>
                        <option value="digital">Digital</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="form-label" for="brand">Product Brand</label>
                      <select class="form-control" name="brand" value="{{ old('brand') }}"/>
                        <option selected disabled>Choose Brand</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="form-label" for="parent_category">Category</label>
                      <select class="form-control dynamic_category" name="parent_category"/>
                        <option selected disabled>Choose Category</option>
                        @foreach($parent_categories as $parent_category)
                            <option value="{{ $parent_category->id }}">{{ $parent_category->name }}</option>
                        @endforeach

                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="form-label" for="child_category">Sub Category</label>
                      <select class="form-control populate_sub_category" name="child_category"/>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="form-label" for="unit">Product Unit</label>
                      <input class="form-control" name="unit" type="text" value="{{ old('unit') }}"/>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="form-label" for="qty">Product Quantity</label>
                      <input class="form-control" name="qty" type="number" value="{{ old('qty') }}"/>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="form-label" for="cost">Product Cost</label>
                      <input class="form-control" name="cost" type="number" value="{{ old('cost') }}"/>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="form-label" for="price">Product Price</label>
                      <input class="form-control" name="price" type="number" value="{{ old('price') }}"/>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label class="form-label" for="featured">Featured </label>
                      <input name="featured" type="checkbox"/>
                      <br>
                      <small><i>Featured product will be displayed in POS</i></small>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="form-label" for="promotional_price">Promotional Price</label>
                      <input class="form-control" name="promotional_price" type="number" value="{{ old('promotional_price') }}"/>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="form-label" for="promotional_start">Promotional Start</label>
                      <input class="form-control" name="promotional_start" type="date" value="{{ old('promotional_start') }}"/>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="form-label" for="promotional_end">Promotional End</label>
                      <input class="form-control" name="promotional_end" type="date" value="{{ old('promotional_end') }}" />
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="form-label" for="tax_method">Tax Method</label>
                      <select class="form-control" name="tax_method" value="{{ old('tax_method') }}" />
                        <option selected disabled>Choose Tax Method</option>
                        <option value="exclusive">Exclusive</option>
                        <option value="inclusive">inclusive</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="form-label" for="tax">Product Tax</label>
                      <select class="form-control" name="tax" value="{{ old('tax') }}" />
                        <option selected disabled>Choose Product Tax</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <label class="form-label" for="details">Product Details</label>
                      <textarea class="form-control" name="details" rows="5">{{ old('details') }}</textarea>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="form-group">
                      <button type="submit" class="btn btn-primary mr-2 px-5 float-right">Add Product</button>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
    </div>
  </section>
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
        var productApp = angular.module('productApp', ['angular.filter']);

        productApp.controller('productCtrl', function ($scope, $http, $sce, $compile) {

            var vm = this;
            
            $('.dynamic_category').change(function(){

                $http({
                    method: 'POST',
                    url: '/category-populate-data',
                    data: JSON.stringify({
                        category: $(this).val()
                    })
                 }).then(function successCallback(response) {

                      if (response.data.status == 'success'){

                          $('.populate_sub_category').html(response.data.responseHtml);

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

            });

        });

    })();
</script>
@stop