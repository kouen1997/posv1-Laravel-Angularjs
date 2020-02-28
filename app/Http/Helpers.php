<?php

function getCategoryExists($category_id){

	$category = App\Models\Category::where('id', $category_id)->first();

	$has_child = $category->children()->first();
	$has_product_parent = App\Models\Products::where('parent_id', $category->id)->first();
	$has_product_child = App\Models\Products::where('child_id', $category->id)->first();

	return array(
		'has_child' => $has_child,
		'has_product_parent' => $has_product_parent,
		'has_product_child' => $has_product_child
	);
}

function adminDashboardData(){

	// Users

    $admin_count = App\Models\User::where('role', 0)->count();

    $employee_count = App\Models\User::where('role', 1)->count();

    $seller_count = App\Models\User::where('role', 2)->count();

    $user_count = App\Models\User::where('role', 3)->count();

    $total_users = App\Models\User::count();

    // Sales

    $daily_sale = App\Models\Purchase::whereDate('created_at', '>=', Carbon\Carbon::now()->today())
                            ->whereDate('created_at', '<=', Carbon\Carbon::now()->today())
                            ->sum('grand_total');

    $weekly_sale = App\Models\Purchase::whereDate('created_at', '>=', Carbon\Carbon::now()->startOfWeek())
                            ->whereDate('created_at', '<=', Carbon\Carbon::now()->endOfWeek())
                            ->sum('grand_total');

    $monthly_sale = App\Models\Purchase::whereDate('created_at', '>=', Carbon\Carbon::now()->startOfMonth())
                            ->whereDate('created_at', '<=', Carbon\Carbon::now()->endOfMonth())
                            ->sum('grand_total');

    $yearly_sale = App\Models\Purchase::whereDate('created_at', '>=', Carbon\Carbon::now()->startOfYear())
                            ->whereDate('created_at', '<=', Carbon\Carbon::now()->endOfYear())
                            ->sum('grand_total');

    $total_sales = App\Models\Purchase::sum('grand_total');

	return array(
        'admin_count' => $admin_count,
        'employee_count' => $employee_count,
        'seller_count' => $seller_count,
        'user_count' => $user_count,
        'total_users' => $total_users,
        'daily_sale' => $daily_sale,
        'weekly_sale' => $weekly_sale,
        'monthly_sale' => $monthly_sale,
        'yearly_sale' => $yearly_sale,
        'total_sales' => $total_sales
	);
}


?>