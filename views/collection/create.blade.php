@extends('core::master')
@section('title')
    Collection
@endsection
@section('content')
    <div id="page-wrapper" data-ng-controller="CreateCollectionController">
        <div class="container-fluid hidden">
            <div class="modal-body user-modal">

            	<div class="col-lg-12">
                    <h3 class="page-header">
                    	@if(!empty($item->id))
                    		Edit Collection @{{$item.name}}
						@else
							Create Collection
						@endif
                    </h3>
                </div>
				<form  class="" method="POST" accept-charset="UTF-8" name="formAddCollection" ng-init='collectionItem={{$item}}; filesUpload={{json_encode($filesUpload)}}'>
					<!-- Title -->
					<div class="form-group">
						<label for="title">Status<small> (*)</small></label>
						<select class="form-control status-collection" ng-model="collectionItem.status" ng-init="collectionItem.status = 'published'">
						  	<option value="published">Published</option>
						  	<option value="pending">Pending</option>
						  	<option value="draft">Draft</option>
						</select>
					</div>
					<!-- End title -->
					<!-- Title -->
					<div class="form-group" ng-class="{'has-error':formAddCollection.title.$touched && formAddCollection.title.$invalid}">
						<label for="title">Title<small> (*)</small></label>
						<input class="form-control" placeholder="Title" type="text" name="title" id="title" value="" ng-model="collectionItem.title" ng-required="true">
						<label class="control-label" ng-show="formAddCollection.title.$touched && formAddCollection.title.$invalid">
							Title is a invalid.
						</label>
					</div>
					<!-- End title -->
					<!-- Content -->
					<div class="form-group" ng-class="{true: 'has-error'}[submitted && requiredContent]">
                        <label for="last_name">Content<small> (*)</small></label>
                        <div class="">
                            <textarea class="form-control" rows="5" id="content" name="content"
                                      ng-model="collectionItem.content">
                            </textarea>
                            <label class="control-label" ng-show="submitted && requiredContent">Content is a required field.</label>
                        </div>
                    </div>
                    <!-- End content -->
					<!-- Description -->
					<div class="form-group">
						<label for="description">Description</label>
						<textarea class="form-control" rows="5" id="descrtiption" placeholder="Descrtiption" ng-model="collectionItem.description"></textarea>
					</div>
					<!-- End description -->
					<!-- Images -->
					<div class="form-group" >
                        <label for="last_name" style="margin-top: 15px;">Collection image </label>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-none">
                            <file-upload ng-model="fileUploaded" file-type="'/png'" multiple-file="true" is-saved="isSavedData" on-redirect="onRedirect(redirect)" files-upload="filesUpload" ></file-upload>
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
					<button ng-disabled="formAddCollection.$invalid" class="btn btn-primary" ng-click="createCollection(collectionItem.id ? 'Save' : 'Add')"><i class="fa fa-plus"></i> @{{collectionItem.id ? 'Save' : 'Add'}}</button>
					<button class="btn btn-default" ng-click="cancel()"> <i class="fa fa-times"></i>Cancel</button>
				</div>
			</div>
        </div>
    </div>
@endsection
@section('script')
 	<script>
        window.linkUpload = '/api/collection/file';
    </script>
    {!! Html::script('core/shared/file-upload/fileUploadDirective.js?v='.getVersionScript())!!}
    {!! Html::script('core/shared/file-upload/fileService.js?v='.getVersionScript())!!}
    {!! Html::script('core/components/collection/CollectionService.js?v='.getVersionScript())!!}
    {!! Html::script('core/components/collection/CollectionController.js?v='.getVersionScript())!!}
@endsection
