@extends('core::master')
@section('title')
    Profile
@endsection
@section('content')
<div id="page-wrapper" class="user-profile hidden" data-ng-controller="UserProfileControler">
    <div class="content content-profile" id="module_profile">
        <div class="user-profile-info" ng-init="userProfile={{json_encode($item)}}">
            <!-- Div avatar user -->
            <div class="col-fix col-xs-4 col-sm-6 col-md-4 col-lg-2">
                <div class="text-center relative-awata">
                    <img ng-if="userProfile.avatar" ng-src="/core/avatars/@{{userProfile.avatar}}" style="width:100%">   
                    <a class="camera" type="submit" 
                        ng-model="file" 
                        accept="image/*" 
                        ngf-select
                        ngf-change="upload($files)">
                    <i class="fa fa-camera"></i>
                    </a>
                </div>
                <div class="btn-change">
                    <button class="btn btn-primary full-for-image" ng-click="getModalChangePassword(userProfile.id)" style="">
                    <span class="glyphicon glyphicon-retweet"></span> 
                    Change Password
                    </button>
                </div>
            </div>
            <!-- End div avatar user -->
            <!-- Div user information -->
            <div class="col-fix personal-info col-sm-6 col-xs-8 col-md-4 col-lg-5">
                <div>
                    <dl class="dl-horizontal">
                        <h3>User profile</h3>
                        <p class="first">
                        <p><strong>Last name: </strong>
                            <span> 
                            <a editable-text="userProfile.last_name" 
                                e-ng-model="userProfile.last_name"
                                onbeforesave="checkLastName($data)">
                            @{{userProfile.last_name || 'empty'}}
                            </a>
                            </span>
                        </p>
                        <strong>First name: </strong>
                        <span> 
                            <a editable-text="userProfile.first_name"  
                                e-ng-model="userProfile.first_name"
                                onbeforesave="checkFirstName($data)">
                            @{{userProfile.first_name || 'empty'}} 
                            </a>
                        </span>
                        <p class="first">
                        <p></p>
                        <strong>Email: </strong>
                        <span>
                            <a editable-text="userProfile.email" 
                                e-name="userProfile.email" 
                                onbeforesave="checkEmail($data,userProfile.id)" >
                            @{{userProfile.email || 'empty'}} 
                            </a>
                        </span>
                        </p>   
                        </p>                
                    </dl>
                </div>
            </div>
            <!-- End div user information -->
            <div class="clearfix"></div>
            <div class="alert alert-error alert-danger" ng-show="error">
                @{{error}}
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
@endsection
@section('script')
<script>
    window.userProfile = {!! json_encode($item) !!}
</script>
{!! Html::script('core/components/user/UserProfileService.js?v='.getVersionScript())!!}
{!! Html::script('core/components/user/UserProfileController.js?v='.getVersionScript())!!}
@endsection