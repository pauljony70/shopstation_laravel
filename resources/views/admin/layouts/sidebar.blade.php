<!-- ========== Left Sidebar Start ========== -->
<div class="left-side-menu">

    <div class="h-100" data-simplebar>

        <!-- User box -->
        <div class="user-box text-center">
            <img src="" alt="user-img" title="Mat Helme" class="rounded-circle avatar-md">
            <div class="dropdown">
                <a href="javascript: void(0);" class="text-dark dropdown-toggle h5 mt-2 mb-1 d-block"
                    data-toggle="dropdown">Admin</a>
                <div class="dropdown-menu user-pro-dropdown">

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-user mr-1"></i>
                        <span>My Account</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-settings mr-1"></i>
                        <span>Settings</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-lock mr-1"></i>
                        <span>Lock Screen</span>
                    </a>

                    <!-- item-->
                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i class="fe-log-out mr-1"></i>
                        <span>Logout</span>
                    </a>

                </div>
            </div>
            <p class="text-muted">Admin Head</p>
        </div>

        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <ul id="side-menu">

                <li class="menu-title">Navigation</li>

                <li>
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span> Dashboard </span>
                    </a>
                </li>

                <!-- basic -->
                <li>
                    <a href="#basic" data-toggle="collapse">
                        <i class="fas fa-cog"></i>
                        <span> Basic </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="basic">
                        <ul class="nav-second-level">
                            <li>
                                <a href="{{ route('admin.brands.index') }}">Brand</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.category.index') }}">Category</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.attribute.index') }}">Attribute</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.attribute_set.index') }}">Attribute Set</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- portals -->
                <li>
                    <a href="{{ route('admin.product.index') }}">
                        <i class="fas fa-search"></i>
                        <span> Products </span>
                    </a>
                </li>


            </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>
<!-- Left Sidebar End -->
