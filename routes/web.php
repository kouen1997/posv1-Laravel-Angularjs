<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// # Login  -- #

Route::get('/login', array('as' => 'login','uses' => 'Auth\LoginController@getLogin'));

Route::post('/login', 'Auth\LoginController@postLogin');

// # Logout  -- #

Route::get('/logout', array('as' => 'logout','uses' => 'Auth\LoginController@logout'));

// # Register -- #

Route::get('/register', array('as' => 'register','uses' => 'Auth\RegisterController@getRegister'));
Route::post('/register', 'Auth\RegisterController@postRegister');

// # Admin Dashboard -- #

Route::get('/admin/dashboard', 'AdminController@getAdminDashboard');

Route::get('/admin-dashboard-data', 'AdminController@getAdminDashboardData');

// # POS -- #

Route::get('/POS', 'PosController@getPos');

Route::get('/pos-product-data', 'PosController@getPosProduct');

Route::post('/add-gift-card/{card_code}', 'PosController@getPosGiftCard');

Route::post('/add-pos-purchase', 'PosController@getPosPurchase');

// # Store -- #

Route::get('/store', 'StoreController@getStore');

Route::get('/store-data', 'StoreController@getStoreData');

Route::post('/addstore', 'StoreController@postAddStore');

Route::get('/editstore/{store_id}', 'StoreController@getEditStore');

Route::post('/editstore/{store_id}', 'StoreController@postEditStore');

Route::post('/deletestore/{store_id}', 'StoreController@postDeleteStore');

// # Product -- #

Route::get('/product/products', 'ProductController@getProduct');

Route::get('/product-data', 'ProductController@getProductData');

Route::get('/product/addproduct', 'ProductController@getAddProduct');

Route::post('/category-populate-data', 'ProductController@getCategoryPupulateData');

Route::post('/product/addproduct', 'ProductController@postAddProduct');


// # Category -- #

Route::get('/product/category', 'CategoryController@getCategory');

Route::get('/category-data', 'CategoryController@getCategoryData');

Route::get('/category-populate-option-data', 'CategoryController@getCategoryPopulateOptionData');

Route::post('/addcategory', 'CategoryController@postAddCategory');

Route::get('/editcategory/{category_id}', 'CategoryController@getEditCategory');

Route::post('/editcategory/{category_id}', 'CategoryController@postEditCategory');

Route::post('/deletecategory/{category_id}', 'CategoryController@postDeleteCategory');


// # Brand -- #

Route::get('/product/brand', 'BrandController@getBrand');

Route::get('/brand-data', 'BrandController@getBrandData');

Route::post('/addbrand', 'BrandController@postAddBrand');

Route::get('/editbrand/{brand_id}', 'BrandController@getEditBrand');

Route::post('/editbrand/{brand_id}', 'BrandController@postEditBrand');

Route::post('/deletebrand/{brand_id}', 'BrandController@postDeleteBrand');

// # Coupon -- #

Route::get('/product/coupon', 'CouponController@getCoupon');

Route::get('/coupon-data', 'CouponController@getCouponData');

Route::post('/add-coupon', 'CouponController@postAddCoupon');

// # Expense Category -- #

Route::get('/expense/expensecategory', 'ExpenseCategoryController@getExpenseCategory');

Route::get('/expense-category-data', 'ExpenseCategoryController@getExpenseCategoryData');

Route::post('/add-expense-category', 'ExpenseCategoryController@postAddExpenseCategory');

Route::get('/edit-expense-category/{expense_category_id}', 'ExpenseCategoryController@getEditExpenseCategory');

Route::post('/edit-expense-category/{expense_category_id}', 'ExpenseCategoryController@postEditExpenseCategory');

Route::post('/delete-expense-category/{expense_category_id}', 'ExpenseCategoryController@postDeleteExpenseCategory');

// # Expense -- #

Route::get('/expense/expenses', 'ExpenseController@getExpense');

Route::get('/expense-data', 'ExpenseController@getExpenseData');

Route::post('/add-expense', 'ExpenseController@postAddExpense');

Route::get('/edit-expense/{expense_id}', 'ExpenseController@getEditExpense');

Route::post('/edit-expense/{expense_id}', 'ExpenseController@postEditExpense');

Route::post('/delete-expense/{expense_id}', 'ExpenseController@postDeleteExpense');

// # Purchase -- #

Route::get('/purchase/list', 'PurchaseController@getPurchase');

Route::get('/purchase-list-data', 'PurchaseController@getPurchaseData');

Route::get('/show-purchases/{purchase_id}', 'PurchaseController@getItemPurchaseData');

Route::get('/purchase/return/{invoice_id}', 'PurchaseController@getPurchaseReturn');

// # Sales -- #

Route::get('/sales/daily', 'SalesController@getDailySales');

Route::get('/sales-daily-data', 'SalesController@getDailySalesData');

Route::get('/sales/weekly', 'SalesController@getWeeklySales');

Route::get('/sales-weekly-data', 'SalesController@getWeeklySalesData');

Route::get('/sales/monthly', 'SalesController@getMonthlySales');

Route::get('/sales-monthly-data', 'SalesController@getMonthlySalesData');

Route::get('/sales/yearly', 'SalesController@getYearlySales');

Route::get('/sales-yearly-data', 'SalesController@getYearlySalesData');

// # Inventory -- #

Route::get('/inventory/in', 'InventoryController@getInventoryIn');

Route::get('/inventory-in-data', 'InventoryController@getInventoryInData');

Route::get('/select-store-data', 'InventoryController@getInventoryStoreData');

Route::get('/product-populate-data/{store_id}', 'InventoryController@getPopulateProductData');

Route::post('/inventory-add-in', 'InventoryController@getInventoryAddIn');