@include('/dashboard/body/header')
@auth
    <nav class="sidenav shadow-right sidenav-light">
        <div class="sidenav-menu">
            <div class="nav accordion" id="accordionSidenav">
                <!-- Sidenav Menu Heading (Core)-->
                <div class="sidenav-menu-heading">Core</div>
                <a class="nav-link {{ Request::is('dashboard*') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <div class="nav-link-icon"><i data-feather="activity"></i></div>
                    Dashboard
                </a>
                
                @if(auth()->user()->role == "pharmacist" | auth()->user()->role == "admin" )
                    <!-- Other menu items for merchant and admin -->
                    <a class="nav-link {{ Request::is('pos*') ? 'active' : '' }}" href="{{ route('pos.index') }}">
                        <div class="nav-link-icon"><i class="fa-solid fa-cart-shopping"></i></div>
                        POS
                    </a>
                    <!-- Additional menu items for merchant and admin -->

                <!-- Sidenav Heading (Orders)-->
                <div class="sidenav-menu-heading">Orders</div>
                <!-- Order-related menu items -->

                <!-- Sidenav Heading (Purchases)-->
                <div class="sidenav-menu-heading">Purchases</div>
                <!-- Purchase-related menu items -->

                <!-- Sidenav Heading (Pages)-->
                <div class="sidenav-menu-heading">Pages</div>
                <a class="nav-link {{ Request::is('customers*') ? 'active' : '' }}" href="{{ route('customers.index') }}">
                    <div class="nav-link-icon"><i class="fa-solid fa-users"></i></div>
                    Customers
                </a>
                <a class="nav-link {{ Request::is('suppliers*') ? 'active' : '' }}" href="{{ route('suppliers.index') }}">
                    <div class="nav-link-icon"><i class="fa-solid fa-users"></i></div>
                    Suppliers
                </a>

                <!-- Sidenav Heading (Products)-->
                <div class="sidenav-menu-heading">Products</div>
                <!-- Product-related menu items -->

                <!-- Sidenav Heading (Settings)-->
                <div class="sidenav-menu-heading">Settings</div>
                <a class="nav-link {{ Request::is('users*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                    <div class="nav-link-icon"><i class="fa-solid fa-users"></i></div>
                    Users
                </a>
            </div>
        </div>
        @endif

        @if(in_array(auth()->user()->role, ['pharmacist']))
       <!-- @if(auth()->user()->role == "pharmacist" | auth()->user()->role == "admin" )-->
                    <!-- Other menu items for merchant and admin -->
                    <a class="nav-link {{ Request::is('pos*') ? 'active' : '' }}" href="{{ route('pos.index') }}">
                        <div class="nav-link-icon"><i class="fa-solid fa-cart-shopping"></i></div>
                        POS
                    </a>
                    <!-- Additional menu items for merchant and admin -->

                <!-- Sidenav Heading (Orders)-->
                <div class="sidenav-menu-heading">Orders</div>
                <!-- Order-related menu items -->

                <!-- Sidenav Heading (Purchases)-->
                <div class="sidenav-menu-heading">Purchases</div>
                <!-- Purchase-related menu items -->

                <!-- Sidenav Heading (Pages)-->
                <div class="sidenav-menu-heading">Pages</div>
                <a class="nav-link {{ Request::is('customers*') ? 'active' : '' }}" href="{{ route('customers.index') }}">
                    <div class="nav-link-icon"><i class="fa-solid fa-users"></i></div>
                    Customers
                </a>
                <a class="nav-link {{ Request::is('suppliers*') ? 'active' : '' }}" href="{{ route('suppliers.index') }}">
                    <div class="nav-link-icon"><i class="fa-solid fa-users"></i></div>
                    Suppliers
                </a>

                <!-- Sidenav Heading (Products)-->
                <div class="sidenav-menu-heading">Products</div>
                <!-- Product-related menu items -->

                <!-- Sidenav Heading (Settings)-->
                <div class="sidenav-menu-heading">Settings</div>
                <a class="nav-link {{ Request::is('users*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                    <div class="nav-link-icon"><i class="fa-solid fa-users"></i></div>
                    Users
                </a>
            </div>
        </div>
        @endif

        <!-- Sidenav Footer-->
        <div class="sidenav-footer">
            <div class="sidenav-footer-content">
                <div class="sidenav-footer-subtitle">Logged in as:</div>
                <div class="sidenav-footer-title">{{ auth()->user()->name }}</div>
            </div>
        </div>
    </nav>
@endauth


        <!-- Sidenav Footer-->
        <div class="sidenav-footer">
            <div class="sidenav-footer-content">
                <div class="sidenav-footer-subtitle">Logged in as:</div>
                <div class="sidenav-footer-title">{{ auth()->user()->name }}</div>
            </div>
        </div>
    </nav>
@endauth
