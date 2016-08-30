<div class="modal-header">
<button aria-label="Close" data-dismiss="modal" class="close" type="button" ng-click="cancel()"><span aria-hidden="true">×</span></button>

@if(!empty($item->id))
	<h4 class="modal-title">Edit Permission @{{$item.name}}</h4>
@else
	<h4 class="modal-title">Create Permission</h4>
@endif
	
</div>
<div class="modal-body user-modal">

	<form  class="" method="POST" accept-charset="UTF-8" name="formAddPermission" ng-init='permissionItem={{$item}}'>

		<div class="form-group" ng-class="{'has-error':formAddPermission.name.$touched && formAddPermission.name.$invalid}">
			<label for="name">Name<small> (*)</small></label>
			<input class="form-control" placeholder="Permission Name" type="text" name="name" id="name" value="" ng-model="permissionItem.name" ng-required="true">
			<label class="control-label" ng-show="formAddPermission.name.$touched && formAddPermission.name.$invalid">
				Name is a invalid.
			</label>
		</div>

		<div class="form-group">
			<label for="description">Description</label>
			<textarea class="form-control" rows="5" id="descrtiption" placeholder="Descrtiption" ng-model="permissionItem.description"></textarea>
		</div>

	</form>

	<div class="alert alert-error alert-danger" ng-show="errors">
		@{{errors}}
	</div>
	<div class="alert" ng-show="notice">@{{notice}}</div>
</div>
<div class="modal-footer">
	<div class="form-group center-block">
		<button ng-disabled="formAddPermission.$invalid" class="btn btn-primary" ng-click="createPermission()">@{{permissionItem.id ? 'Save' : 'Add'}}</button>
		<button class="btn btn-default" ng-click="cancel()">Cancel</button>
	</div>
</div>
