@extends('core::master')
@section('title')
	Permission & Role
@stop
@section('content')
<div id="page-wrapper" data-ng-controller="UserController" class="ng-scope" style="min-height: 354px;">
	<div class="container-fluid hidden">
		<div class="row">
			<div class="col-lg-12">
                <h3 class="page-header">Role & Permission Manager</h3>
            </div>
			<div class="col-lg-12">
				<div data-ng-controller="UserController">
					<div class="clearfix"></div>
					<div class="content wrap-permission-user">
						<h4 class="title-multiselect">Roles</h4>
						<multi-select placeholder="Roles" items="listRoles" items-assigned="userRoles" on-change="updateRole()"> </multi-select>
						<h4 class="title-multiselect">Permissions</h4>
						<multi-select items="listPermissions" items-assigned="userPermissions" on-change="updatePermission()"> </multi-select>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@stop
@section('script')
	<script  type="text/javascript">
	    window.dataController = {
	    	'id': {{$id}},
	    	'listPermissions' : {!!json_encode($listPermissions)!!},
	    	'userPermissions' : {!!empty($userPermissions)? '{}': json_encode($userPermissions)!!},
	    	'listRoles'       : {!!json_encode($listRoles)!!},
	    	'userRoles'       : {!!empty($userRoles)? '{}': json_encode($userRoles)!!}
	    }
	</script>
	{!! Html::script('core/components/user/UserService.js')!!}
	{!! Html::script('core/components/user/UserController.js')!!}
	{!! Html::script('core/shared/multi-select/multiSelectDirective.js')!!}
@stop
