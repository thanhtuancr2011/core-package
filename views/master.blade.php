<!DOCTYPE html>
<html lang="en" data-ng-app="core">
    <head>
        <title>
            @yield('title')
        </title>
        @include('core::shared.head')
    </head>
    <body>
        <div id="wrapper">
            <!-- Navigation -->
            <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">

            	<!-- Navigation -->
            		@include('core::shared.navbar')
            	<!-- End Navigation -->

            	<!-- Side bar -->
            		@include('core::shared.sidebar')
            	<!-- End Side bar -->
            	
            </nav>

            <!-- Page Content -->
            @yield('content')
            <!-- End Page Content -->

        </div>
        <!-- /#wrapper -->
    </body>
    @include('core::shared.script')
</html>
