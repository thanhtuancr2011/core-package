<div class="modal-header">
<button aria-label="Close" data-dismiss="modal" class="close" type="button" ng-click="cancel()"><span aria-hidden="true">×</span></button>

@if(!empty($item->id))
	<h4 class="modal-title">Edit Role @{{$item.name}}</h4>
@else
	<h4 class="modal-title">Create Role</h4>
@endif
	
</div>
<div class="modal-body user-modal">

	<form  class="" method="POST" accept-charset="UTF-8" name="formAddRole" ng-init='roleItem={{$item}}'>

		<div class="form-group" ng-class="{'has-error':formAddRole.name.$touched && formAddRole.name.$invalid}">
			<label for="name">Name<small> (*)</small></label>
			<input class="form-control" placeholder="Role Name" type="text" name="name" id="name" value="" ng-model="roleItem.name" ng-required="true">
			<label class="control-label" ng-show="formAddRole.name.$touched && formAddRole.name.$invalid">
				Name is a invalid.
			</label>
		</div>

		<div class="form-group">
			<label for="description">Description</label>
			<textarea class="form-control" rows="5" id="descrtiption" placeholder="Descrtiption" ng-model="roleItem.description"></textarea>
		</div>

	</form>

	<div class="alert alert-error alert-danger" ng-show="errors">
		@{{errors}}
	</div>
	<div class="alert" ng-show="notice">@{{notice}}</div>
</div>
<div class="modal-footer">
	<div class="form-group center-block">
		<button ng-disabled="formAddRole.$invalid" class="btn btn-primary" ng-click="createRole()">@{{roleItem.id ? 'Save' : 'Add'}}</button>
		<button class="btn btn-default" ng-click="cancel()">Cancel</button>
	</div>
</div>
