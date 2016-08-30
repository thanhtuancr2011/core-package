<div class="modal-header">
<button aria-label="Close" data-dismiss="modal" class="close" type="button" ng-click="cancel()"><span aria-hidden="true">×</span></button>

@if(!empty($item->id))
	<h4 class="modal-title">Edit User @{{$item.first_name}} @{{$item.last_name}}</h4>
@else
	<h4 class="modal-title">Create User</h4>
@endif
	
</div>
<div class="modal-body user-modal">
	<form  class="" method="POST" accept-charset="UTF-8" name="formAddUser" ng-init='userItem={{$item}}'>
		<div class="form-group" ng-class="{'has-error':formAddUser.first_name.$touched && formAddUser.first_name.$invalid}">
			<label for="first_name">First Name<small> (*)</small></label>
			<input class="form-control"  placeholder="First name" type="text" name="first_name" id="first_name" value="" ng-model="userItem.first_name" ng-required="true">
			<label class="control-label" ng-show="formAddUser.first_name.$touched && formAddUser.first_name.$invalid">
				First Name is a invalid.
			</label>
		</div>

		<div class="form-group" ng-class="{'has-error':formAddUser.last_name.$touched && formAddUser.last_name.$invalid}">
			<label for="last_name">Last Name<small> (*)</small></label>
			
			<input class="form-control"  placeholder="Last Name" type="text" name="last_name" id="last_name" value="" ng-model="userItem.last_name" ng-required="true">
			<label class="control-label" ng-show="formAddUser.last_name.$touched && formAddUser.last_name.$invalid">
				Last Name is a invalid.
			</label>
		</div>

		<div class="form-group" ng-class="{'has-error':formAddUser.email.$touched && formAddUser.email.$invalid}">
			<label for="email">Email <small> (*)</small></label>
			<input ng-pattern="/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/" class="form-control" placeholder="Email" type="text" name="email" id="email"  ng-model="userItem.email" ng-required="true">
			<label class="control-label" ng-show="formAddUser.email.$touched && formAddUser.email.$invalid" >
				Email is a invalid.
			</label>
		</div>

		<div class="form-group">
			<label for="email">Description</label>
			<input class="form-control" placeholder="Description" type="text" name="description" id="description" ng-model="userItem.description">
		</div>

		<div class="form-group" ng-class="{'has-error':formAddUser.phone_number.$touched && formAddUser.phone_number.$invalid}">
			<label for="phone_number">Phone</label>
			<div class="">
				<input class="form-control" type="text" name="phone_number" id="phone_number" placeholder="Phone" 
						ng-model="userItem.personal_information.phone_number"
						data-ng-init='formatPhoneNumber()' ng-blur="validatePhone(userItem.personal_information.phone_number)">
				<label  class="control-label" ng-show="formAddUser.phone_number.$touched && formAddUser.phone_number.$invalid">
					Not a phone number.
				</label>
			</div>
		</div>

		<!-- If create user -->
        <div ng-if="!userItem.id" class="form-group" ng-class="{'has-error':formAddUser.password.$touched && formAddUser.password.$invalid}">
            <label for="inputPassword">Password<small> (*)</small></label>
            <div class="">
                <input class="form-control" placeholder="Password" type="password" name="password" id="password" ng-model="userItem.password" ng-required="true">
                <label class="control-label" ng-show="formAddUser.password.$touched && formAddUser.password.$invalid" >
                    Password is a invalid.
                </label>
            </div>
        </div>

        <div ng-if="!userItem.id" class="form-group" ng-class="{'has-error':formAddUser.rePassword.$touched && (formAddUser.rePassword.$invalid || userItem.rePassword != userItem.password)}">
            <label for="inputRePassword">Retype Password<small> (*)</small></label>
            <div class="">
                <input class="form-control" placeholder="Retype password" type="password" name="rePassword" id="rePassword" ng-model="userItem.rePassword" ng-required="true">
                <label class="control-label" ng-show="formAddUser.rePassword.$touched && formAddUser.rePassword.$error.required" >
                    Please retype password
                </label>
                <label class="control-label" ng-show="formAddUser.rePassword.$touched && userItem.rePassword != userItem.password && !formAddUser.rePassword.$error.required" >
                    Retype password is a invalid
                </label>
            </div>
        </div>

        <!-- If edit user -->
        <div ng-if="userItem.id" class="form-group" ng-class="{'has-error':formAddUser.password.$touched && formAddUser.password.$invalid}">
            <label for="inputPassword">Password<small> (*)</small></label>
            <div class="">
                <input class="form-control" placeholder="Password" type="password" name="password" id="password" ng-model="userItem.password">
                <label class="control-label" ng-show="formAddUser.password.$touched && formAddUser.password.$invalid" >
                    Password is a invalid.
                </label>
            </div>
        </div>

        <div ng-if="userItem.id" class="form-group" ng-class="{'has-error':formAddUser.rePassword.$touched && (formAddUser.rePassword.$invalid || userItem.rePassword != userItem.password)}">
            <label for="inputRePassword">Retype Password<small> (*)</small></label>
            <div class="">
                <input class="form-control" placeholder="Retype password" type="password" name="rePassword" id="rePassword" ng-model="userItem.rePassword">
                <label class="control-label" ng-show="formAddUser.rePassword.$touched && userItem.rePassword != userItem.password && !formAddUser.rePassword.$error.required" >
                    Retype password is a invalid
                </label>
            </div>
        </div>
	</form>
	<div class="alert alert-error alert-danger" ng-show="error">
		@{{error}}
	</div>
	<div class="alert" ng-show="notice">@{{notice}}</div>
</div>
<div class="modal-footer">
	<div class="form-group center-block">
		<button ng-disabled="formAddUser.$invalid || userItem.rePassword != userItem.password" class="btn btn-primary" ng-click="createUser()">@{{userItem.id ? 'Save' : 'Add'}}</button>
		<button class="btn btn-default" ng-click="cancel()">Cancel</button>
	</div>
</div>
