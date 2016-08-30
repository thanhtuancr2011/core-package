<div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="">Administration Website</a>
</div>
<!-- /.navbar-header -->

<ul class="nav navbar-top-links navbar-right">
    <!-- /.dropdown -->
    <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
            @if (Auth::user()->avatar)
                <img class="img-circle" src="{{URL::to('core/avatars')}}/{{ Auth::user()->avatar}}" alt="" height="40"> <i class="fa fa-caret-down"></i>
            @else
                <img class="img-circle" src="{{URL::to('core/avatars')}}/50x50_avatar_default.png" alt="" height="40"> <i class="fa fa-caret-down"></i>
            @endif
        </a>

        <ul class="dropdown-menu dropdown-user">
            <li><a href="{{URL::to('user/profile')}}/{{ Auth::user()->id}}"><i class="fa fa-user fa-fw"></i> My Profile</a>
            </li>
            <li class="divider"></li>
            <li><a href="{{URL::to('auth/logout')}}"><i class="fa fa-sign-out fa-fw"></i> Sign Out</a>
            </li>
        </ul>
        <!-- /.dropdown-user -->
    </li>
    <!-- /.dropdown -->
</ul>
<!-- /.navbar-top-links -->