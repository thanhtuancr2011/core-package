@extends('core::master')
@section('title')
    {{($type != 'page')  ? 'Article' : 'Page'}}
@endsection
@section('content')
    <div id="page-wrapper" data-ng-controller="CreateArticleController">
        <div class="container-fluid hidden">
            <div class="modal-body user-modal">

            	<div class="col-lg-12">
                    <h3 class="page-header">
                    	@if(!empty($item->id))
                    		{{($type != 'page')  ? 'Edit Article' : 'Edit Page'}} @{{$item.name}}
						@else
							{{($type != 'page')  ? 'Create Article' : 'Create Page'}}
						@endif
                    </h3>
                </div>
				<form  class="" method="POST" accept-charset="UTF-8" name="formAddArticle" ng-init='articleItem={{$item}}; filesUpload={{json_encode($filesUpload)}}'>
					<!-- Title -->
					<div class="form-group">
						<label for="title">Status<small> (*)</small></label>
						<select class="form-control status-article" ng-model="articleItem.status" ng-init="articleItem.status = 'published'">
						  	<option value="published">Published</option>
						  	<option value="pending">Pending</option>
						  	<option value="draft">Draft</option>
						</select>
					</div>
					@if ($type == 'article') 
						<input type="hidden" name="type" ng-model="articleItem.type" ng-init="articleItem.type = 'article'">
					@else
						<input type="hidden" name="type" ng-model="articleItem.type" ng-init="articleItem.type = 'page'">
					@endif
					<!-- End title -->
					<!-- Title -->
					<div class="form-group" ng-class="{'has-error':formAddArticle.title.$touched && formAddArticle.title.$invalid}">
						<label for="title">Title<small> (*)</small></label>
						<input class="form-control" placeholder="Title" type="text" name="title" id="title" value="" ng-model="articleItem.title" ng-required="true">
						<label class="control-label" ng-show="formAddArticle.title.$touched && formAddArticle.title.$invalid">
							Title is a invalid.
						</label>
					</div>
					<!-- End title -->
					<!-- Content -->
					<div class="form-group" ng-class="{true: 'has-error'}[submitted && requiredContent]">
                        <label for="last_name">Content<small> (*)</small></label>
                        <div class="">
                            <textarea class="form-control" rows="5" id="content" name="content"
                                      ng-model="articleItem.content">
                            </textarea>
                            <label class="control-label" ng-show="submitted && requiredContent">Content is a required field.</label>
                        </div>
                    </div>
                    <!-- End content -->
					<!-- Description -->
					<div class="form-group">
						<label for="description">Description</label>
						<textarea class="form-control" rows="5" id="descrtiption" placeholder="Descrtiption" ng-model="articleItem.description"></textarea>
					</div>
					<!-- End description -->
					<!-- Images -->
					<div class="form-group" >
                        <label for="last_name" style="margin-top: 15px;">Article image </label>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-none">
                            <file-upload multiple-file="false" file-type="'/png'" multiple-file="false" is-saved="isSavedData" on-redirect="onRedirect(redirect)" files-upload="filesUpload" ng-model="fileUploaded" ></file-upload>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <!-- End images -->
				</form>
				<div class="alert alert-error alert-danger" ng-show="errors" ng-repeat="(key, value) in errors">
					<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
					@{{value[0]}}
				</div>
			</div>

			<div class="modal-footer">
				<div class="form-group center-block">
					<button ng-disabled="formAddArticle.$invalid" class="btn btn-primary" ng-click="createArticle(articleItem.id ? 'Save' : 'Add')">@{{articleItem.id ? 'Save' : 'Add'}}</button>
					<button class="btn btn-default" ng-click="cancel()">Cancel</button>
				</div>
			</div>
        </div>
    </div>
@endsection
@section('script')
 	<script>
        window.type = {!! json_encode($type) !!}
        window.linkUpload = '/api/article/file';
    </script>
    {!! Html::script('core/shared/file-upload/fileUploadDirective.js?v='.getVersionScript())!!}
    {!! Html::script('core/shared/file-upload/fileService.js?v='.getVersionScript())!!}
    {!! Html::script('core/components/article/ArticleService.js?v='.getVersionScript())!!}
    {!! Html::script('core/components/article/ArticleController.js?v='.getVersionScript())!!}
@endsection
