<style type="text/css">
    ul>li>ul>li .active {
        background-color: red;
    }
</style>
<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            <li class="sidebar-search">
                <div class="input-group custom-search-form">
                    <input type="text" class="form-control" placeholder="Tìm kiếm...">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button">
                            <i class="fa fa-search"></i>
                        </button>
                    </span>
                </div>
                <!-- /input-group -->
            </li>

           {{--  <li>
                <a href="/admin/dashboard"><i class="fa fa-dashboard fa-fw"></i> Control Panel</a>
            </li> --}}

            @if(Auth::user()->is('super.admin'))
                <li @if(Request::is('admin/page')) class="active" @endif>
                    <a href="#"><i class="fa fa-file-text-o fa-fw"></i> Page<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="/admin/page" class="active">Pages</a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
            @endif

            @if(Auth::check() && (Auth::user()->is('super.admin') || Auth::user()->is('super.mod')))
                <li>
                    <a href="#"><i class="fa fa-cube fa-fw"></i> Product<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li @if(Request::is('admin/category')) class="active" @endif>
                            <a href="/admin/category">Categories</a>
                        </li>
                        <li @if(Request::is('admin/product')) class="active" @endif>
                            <a href="/admin/product" >Products</a>
                        </li>
                        <li @if(Request::is('admin/order')) class="active" @endif>
                            <a href="/admin/order" >Orders</a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
            @endif

           	@if(Auth::check() && Auth::user()->is('super.admin'))
                <li>
                    <a href="#"><i class="fa fa-users fa-fw"></i> User<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li @if(Request::is('admin/user')) class="active" @endif>
                            <a href="/admin/user" >Users</a>
                        </li>
                        <li @if(Request::is('admin/role')) class="active" @endif>
                            <a href="/admin/role" >Role</a>
                        </li>
                        <li @if(Request::is('admin/permission')) class="active" @endif>
                            <a href="/admin/permission" >Permission</a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
            @endif
            @if(Auth::user()->is('super.admin'))
                <li @if(Request::is('admin/article')) class="active" @endif>
                    <a href="#"><i class="fa fa-newspaper-o fa-fw"></i> Article<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="/admin/article" class="active">Articles</a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
            @endif

            @if(Auth::user()->is('super.admin'))
                <li @if(Request::is('admin/collection')) class="active" @endif>
                    <a href="#"><i class="fa fa-qrcode fa-fw"></i> Collection<span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="/admin/collection" class="active">Collections</a>
                        </li>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
            @endif
        </ul>
    </div>
    <!-- /.sidebar-collapse -->
</div>