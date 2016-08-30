<!-- Modal change password for user -->
    <div class="modal-header"> 
        <button type="button" class="close" ng-click="cancel()" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Change password</h4>
    </div>
    <div class="modal-body sizer">
        <form name="formChangePassword" method="PUT" accept-charset="UTF-8">
            <input type="hidden" name="_token" id="_token" value="{!! csrf_token() !!}}" />
            
            <div class="form-group" ng-class="{'has-error':formChangePassword.password.$touched && formChangePassword.password.$invalid}" style="margin-bottom:10px;">
                <label for="new_password">New password</label>
                <div>
                    <input type="password" name="password" ng-model="user.password" class="form-control" id="new_password" ng-required="true">
                    <label class="control-label" ng-show="formChangePassword.password.$touched && formChangePassword.password.$error.required">
                        New password is a required field.
                    </label>
                </div>
            </div>

            <div ng-class="{'has-error':formChangePassword.password_confirmation.$touched && (formChangePassword.password_confirmation.$invalid || user.password_confirmation != user.password)}" type="password" class="form-group" style="margin-bottom:20px;">
                <label for="confirm_new_password">Retype password</label>
                <div>
                    <input  type="password" class="form-control" id="confirm_new_password" name="password_confirmation" ng-model="user.password_confirmation" ng-required="true">
                    <label class="control-label" ng-show="formChangePassword.password_confirmation.$touched && formChangePassword.password_confirmation$invalid">
                        Retype password is a required field.
                    </label>
                    <label class="control-label" ng-show="formChangePassword.password_confirmation.$touched && user.password_confirmation!=user.password">
                        Retype password is a invalid.
                    </label>
                </div>
            </div>

            <div class="form-group row">
                <div class="alert alert-error alert-danger" ng-show="error">
                    @{{error}}
                </div>

                <div class="alert alert-success" ng-show="message_success">
                    @{{message_success}}
                </div>
            </div>
            <div class="modal-footer">
                <div class="form-group center-block"> 
                    <button ng-disabled="formChangePassword.$invalid || user.password_confirmation!=user.password" 
                            class="btn btn-primary" ng-click="submit(userProfile.id)">
                        <span class="glyphicon glyphicon-retweet"></span> Change password
                    </button>
                    <button type="button" class="btn btn-primary" ng-click="cancel()"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
                </div>
            </div>
        </form>
    </div>
<!-- End modal change password for user -->