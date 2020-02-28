<div class="main-sidebar sidebar-style-2">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <a href="index-2.html">Posv1</a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
      <a href="index-2.html">St</a>
    </div>
    <ul class="sidebar-menu">
      <li class="{{ Request::is('admin/dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/admin/dashboard') }}">
          <i class="fas fa-fire"></i>
          <span>Dashboard</span>
        </a>
      </li>
      <li class="{{ Request::is('POS') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/POS') }}">
          <i class="fas fa-shopping-cart"></i>
          <span>POS</span>
        </a>
      </li>
      <li class="{{ Request::is('store') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/store') }}">
          <i class="fas fa-store"></i>
          <span>Store</span>
        </a>
      </li>
      <li class="menu-header">Menu</li>
      <li class="dropdown {{ Request::is('product/*') ? 'active' : '' }}">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
          <i class="fas fa-shopping-basket"></i> 
          <span>Product</span>
        </a>
        <ul class="dropdown-menu">
          <li class="{{ Request::is('product/products') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('/product/products') }}">Products</a>
          </li>
          <li class="{{ Request::is('product/category') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('/product/category') }}">Category</a>
          </li>
          <li class="{{ Request::is('product/brand') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('/product/brand') }}">Brand</a>
          </li>
          <li class="{{ Request::is('product/coupon') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('/product/coupon') }}">Coupon</a>
          </li>
        </ul>
      </li>
       <li class="dropdown {{ Request::is('purchase/*') ? 'active' : '' }}">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
          <i class="fas fa-calculator"></i> 
          <span>Purchase</span>
        </a>
        <ul class="dropdown-menu">
          <li class="{{ Request::is('purchase/list') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('/purchase/list') }}">Purchases</a>
          </li>
        </ul>
      </li>
      <li class="dropdown {{ Request::is('expense/*') ? 'active' : '' }}">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
          <i class="fas fa-money-check-alt"></i>
          <span>Expense</span>
        </a>
        <ul class="dropdown-menu">
          <li class="{{ Request::is('expense/expenses') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('/expense/expenses') }}">Expenses</a>
          </li>
          <li class="{{ Request::is('expense/expensecategory') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('/expense/expensecategory') }}">Expense Category</a>
          </li>
        </ul>
      </li>
      <li class="dropdown {{ Request::is('sales/*') ? 'active' : '' }}">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
          <i class="fas fa-chart-area"></i>
          <span>Sales Report</span>
        </a>
        <ul class="dropdown-menu">
          <li class="{{ Request::is('sales/daily') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('/sales/daily') }}">Daily</a>
          </li>
          <li class="{{ Request::is('sales/weekly') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('/sales/weekly') }}">Weekly</a>
          </li>
          <li class="{{ Request::is('sales/monthly') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('/sales/monthly') }}">Monthly</a>
          </li>
          <li class="{{ Request::is('sales/yearly') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('/sales/yearly') }}">Yearly</a>
          </li>
        </ul>
      </li>
      <li class="dropdown {{ Request::is('inventory/*') ? 'active' : '' }}">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
          <i class="fas fa-list-alt"></i>
          <span>Inventory Report</span>
        </a>
        <ul class="dropdown-menu">
          <li class="{{ Request::is('inventory/in') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('/inventory/in') }}">IN</a>
          </li>
          <li class="{{ Request::is('inventory/out') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('/inventory/out') }}">Out</a>
          </li>
          <li class="{{ Request::is('inventory/purchase') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('/inventory/purchase') }}">Purchase</a>
          </li>
          <li class="{{ Request::is('inventory/transfer') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('/inventory/transfer') }}">Transfer</a>
          </li>
          <li class="{{ Request::is('inventory/return') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('/inventory/return') }}">Return</a>
          </li>
        </ul>
      </li>
      <li class="dropdown {{ Request::is('settings/*') ? 'active' : '' }}">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
          <i class="fas fa-cogs"></i>
          <span>Settings</span>
        </a>
        <ul class="dropdown-menu">
          <li class="{{ Request::is('settings/expenses') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('/settings/expenses') }}">Expenses</a>
          </li>
          <li class="{{ Request::is('settings/expensecategory') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('/settings/expensecategory') }}">Expense Category</a>
          </li>
        </ul>
      </li>
    </ul>      
  </aside>
</div>
