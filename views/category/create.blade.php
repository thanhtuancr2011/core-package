@extends('core::master')
@section('title')
    Category
@stop
@section('content')
    
<!-- Page Content -->
<div id="page-wrapper" class="hidden" data-ng-controller="ModalCreateCategoryCtrl">
    <div class="innerAll">
        <div class="col-lg-12">
            <h3 class="page-header">Create new category</h3>
        </div>
        <div class="innerLR">
            <form method="POST" accept-charset="UTF-8" name="formCategory" ng-init="categoryItem={{$category}}; categorySelected={{$categorySelected}}; filesUpload={{json_encode($filesUpload)}}" novalidate>
                <input type="hidden" name="_token" value="csrf_token()" />
                <div class="form-group" ng-class="{true: 'has-error'}[submitted && requiredCategoryParent]" ng-if="categoriesTree.length > 0">
                    <label for="last_name">Category parent</label>
                    <div class="">
                        <select-level-category items="categoriesTree" text="Category" title="Select category" ng-model="categoryItem.parent_id" selected-item="categorySelected"></select-level-category>
                    </div>
                </div>
                <div class="form-group" ng-class="{true: 'has-error'}[submitted && (formCategory.name.$invalid || nameExists)]">
                    <label for="last_name">Name (*)</label>
                    <div class="">
                        <input class="form-control" placeholder="Category Name" type="text" name="name" id="name" value="" 
                               ng-model="categoryItem.name" 
                               ng-minlength=1
                               ng-maxlength=125
                               ng-required="true">
                        <label class="control-label" ng-show="submitted && nameExists">@{{messageNameExists}}</label>
                        <label class="control-label" ng-show="submitted && formCategory.name.$invalid">Name is a required field.</label>
                        <label class="control-label" ng-show="submitted && formCategory.name.$error.maxlength">Category name up to 125 characters.</label>
                    </div>
                </div>
                <div class="form-group" ng-class="{true: 'has-error'}[submitted && formCategory.sort_order.$invalid]">
                    <label for="last_name">Sort order </label>
                    <div class="">
                        <input class="form-control" ng-init="initSortNumber()" placeholder="Sort order" type="text" name="sort_order" id="sort-order" ng-model="categoryItem.sort_order" ng-required="true">
                        <label class="control-label" ng-show="submitted && formCategory.sort_order.$error.required">Sort order is a required field.</label>
                    </div>
                </div>
                 <div class="form-group" >
                    <label for="last_name">Keyword </label>
                    <div class="">
                        <input class="form-control" placeholder="Keywords" type="text" name="keywords" id="keywords" 
                               ng-model="categoryItem.keywords" 
                               ng-maxlength=250 >
                        <label class="control-label" ng-show="submitted && formCategory.keywords.$error.max">Keywords up to 250 characters.</label>
                    </div>
                </div>
                <div class="form-group" >
                    <label for="last_name">Description </label>
                    <div class="">
                        <textarea class="form-control" rows="5" id="description" name="description" placeholder="Description" 
                                  ng-model="categoryItem.description"
                                  ng-maxlength=500 >
                        </textarea>
                        <label class="control-label" ng-show="submitted && formCategory.keywords.$error.max">Description up to 500 characters.</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="last_name">Category image </label>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-none">
                        <file-upload ng-model="fileUploaded" file-type="'/png'" multiple-file="false" is-saved="isSavedData" on-redirect="onRedirect(redirect)" files-upload="filesUpload" ></file-upload>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" ng-click="submit(formCategory.$invalid, categoryItem.id ? 'Save' : 'Add')"><i class="fa fa-plus"></i> @{{categoryItem.id ? 'Save' : 'Add'}}</button>
            <button class="btn btn-default" ng-click="cancel()"><i class="fa fa-times"></i> Cancel</button>
        </div>
    </div>
</div>

@stop

@section('script')
    <script>
        window.category = {!! json_encode($category) !!};
        window.categoriesTree = {!! json_encode($categoriesTree) !!};
        window.categorySelected = {!! json_encode($categorySelected) !!};
        window.linkUpload = '/api/category/file';
    </script>
    {!! Html::script('core/components/category/CategoryService.js?v='.getVersionScript())!!}
    {!! Html::script('core/components/category/CategoryController.js?v='.getVersionScript())!!}
    {!! Html::script('core/shared/select-category/SelectLevelCategory.js?v='.getVersionScript())!!}
    {!! Html::script('core/shared/file-upload/fileUploadDirective.js?v='.getVersionScript())!!}
    {!! Html::script('core/shared/file-upload/fileService.js?v='.getVersionScript())!!}
@stop
