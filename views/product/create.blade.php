@extends('core::master')
@section('title')
    Create Product
@endsection
@section('content')
    
    <!-- Page Content -->
    <div id="page-wrapper" data-ng-controller="ModalCreateProductCtrl">
        <div class="container-fluid hidden">
            <div class="modal-body">
                <div class="innerAll">
                    <div class="col-lg-12">
                        <h3 class="page-header">Create new product {{$product->name}}</h3>
                    </div>
                    <div class="innerLR">
                        <form method="POST" accept-charset="UTF-8" name="formProduct" ng-init="productItem={{$product}}; categorySelected={{$categorySelected}}; filesUpload={{json_encode($filesUpload)}}">
                            <input type="hidden" name="_token" value="csrf_token()" />
                            <div class="form-group" ng-class="{true: 'has-error'}[submitted && requiredCategory]" ng-if="categoriesTree.length > 0">
                                <label for="last_name">Category</label>
                                <div class="">
                                    <select-level-category items="categoriesTree" text="Category" title="Select category" ng-model="productItem.category_id" selected-item="categorySelected" on-click="chooseCategory(productItem.category_id)"></select-level-category>
                                    <label class="control-label" ng-show="submitted && requiredCategory">Category is a required field.</label>
                                </div>
                            </div>
                            <div class="form-group" ng-class="{true: 'has-error'}[submitted && (formProduct.name.$invalid || nameExists)]">
                                <label for="last_name">Name (*)</label>
                                <div class="">
                                    <input class="form-control" placeholder="Product name" type="text" name="name" id="name" value="" 
                                           ng-model="productItem.name" 
                                           ng-minlength=1
                                           ng-maxlength=125
                                           ng-required="true">
                                    <label class="control-label" ng-show="submitted && nameExists">@{{messageNameExists}}</label>
                                    <label class="control-label" ng-show="submitted && formProduct.name.$error.required">Product name is a required field.</label>
                                    <label class="control-label" ng-show="submitted && formProduct.name.$error.maxlength">Product name up to 125 characters.</label>
                                </div>
                            </div>
                            <div class="form-group" ng-class="{true: 'has-error'}[submitted && formProduct.price.$invalid]">
                                <label for="last_name">SKU (*)</label>
                                <div class="">
                                    <input class="form-control" placeholder="SKU" type="text" name="sku" id="sku" value="" 
                                           ng-model="productItem.sku" 
                                           ng-minlength=1
                                           ng-maxlength=125
                                           ng-required="true">
                                    <label class="control-label" ng-show="submitted && formProduct.name.$error.required">SKU is a required field.</label>
                                    <label class="control-label" ng-show="submitted && formProduct.name.$error.maxlength">SKU up to 125 characters.</label>
                                </div>
                            </div>
                            <div class="form-group" ng-class="{true: 'has-error'}[submitted && formProduct.price.$invalid]">
                                <label for="last_name">Price (*)</label>
                                <div class="">
                                    <input class="form-control" ng-init="initCurrency('price')" placeholder="Price" type="text" name="price" id="price"
                                           ng-model="productItem.price" 
                                           ng-required="true">
                                    <label class="control-label" ng-show="submitted && formProduct.price.$error.required">Price is a required field.</label>
                                </div>
                            </div>
                            <div class="form-group" ng-class="{true: 'has-error'}[submitted && formProduct.old_price.$invalid]">
                                <label for="last_name">Old price </label>
                                <div class="">
                                    <input class="form-control" ng-init="initCurrency('old_price')" placeholder="Old price" type="text" name="old_price" id="old_price" ng-model="productItem.old_price">
                                </div>
                            </div>
                            <div class="form-group" >
                                <label for="last_name">Color </label>
                                <select class="form-control color-product" multiple="multiple" ng-model="productItem.color">
                                    <option value="#FF0000">Đỏ</option>
                                    <option value="#FFFF00">Vàng</option>
                                    <option value="#008000">Xanh lá</option>
                                    <option value="#800080">Tím</option>
                                    <option value="#FFC0CB">Hồng</option>
                                    <option value="#0000FF">Xanh dương</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="comment">Meta desctiption</label>
                                <div class="">
                                    <textarea class="form-control" rows="5" name="meta_description" id="meta-description" ng-model="productItem.meta_description"></textarea>
                                </div>
                            </div>
                            <div class="form-group" ng-class="{true: 'has-error'}[submitted && requiredDescription]">
                                <label for="last_name">Description (*)</label>
                                <div class="">
                                    <textarea class="form-control" rows="5" id="description" name="description" placeholder="Mô tả" 
                                              ng-model="productItem.description">
                                    </textarea>
                                    <label class="control-label" ng-show="submitted && requiredDescription">Description is a required field.</label>
                                </div>
                            </div>
                            <div class="form-group" ng-class="{true: 'has-error'}[submitted && formProduct.origin.$invalid]">
                                <label for="last_name">Origin</label>
                                <div class="">
                                    <input class="form-control" placeholder="Origin" type="text" name="origin" id="origin" 
                                           ng-model="productItem.origin">
                                </div>
                            </div>
                            <div class="form-group" >
                                <label for="last_name">Manufacturer</label>
                                <div class="">
                                    <input class="form-control" placeholder="Manufacturer" type="text" name="manufacturer" id="manufacturer" ng-model="productItem.manufacturer">
                                </div>
                            </div>
                            <div class="form-group" ng-class="{true: 'has-error'}[submitted && formProduct.availibility.$invalid]">
                                <label for="last_name">Availibility </label>
                                <div class="">
                                    <input class="form-control" ng-init="initNumberAvailibility()" placeholder="Number products in stock" type="text" name="availibility" id="availibility" ng-model="productItem.availibility">
                                </div>
                            </div>
                            <div class="form-group" >
                                <label for="last_name">Size</label>
                                <div class="">
                                    <input class="form-control" placeholder="Size" type="text" name="size" id="size" ng-model="productItem.size">
                                </div>
                            </div><div class="form-group" >
                                <label for="last_name">Dimension</label>
                                <div class="">
                                    <input class="form-control" placeholder="Dimension" type="text" name="dimension" id="dimension" ng-model="productItem.dimension">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="last_name">Weight (gram) </label>
                                <div class="">
                                    <input class="form-control" ng-init="initNumberWeight()" placeholder="Weight" type="text" name="weight" id="weight" ng-model="productItem.weight">
                                </div>
                            </div>
                            <div class="form-group" >
                                <label for="last_name">Keywords </label>
                                <div class="">
                                    <input class="form-control" placeholder="Keywords" type="text" name="keywords" id="keywords" 
                                           ng-model="productItem.keywords"
                                           ng-required >
                                    <label class="control-label" ng-show="submitted && formProduct.keywords.$error.max">Keywords up to 250 characters.</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="last_name">Product images </label>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-none">
                                    <file-upload ng-model="fileUploaded" file-type="'/png'" multiple-file="true" is-saved="isSavedData" on-redirect="onRedirect(redirect)" files-upload="filesUpload" ></file-upload>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" ng-click="submit(formProduct.$invalid, productItem.id ? 'Save' : 'Add')"><i class="fa fa-plus"></i> @{{collectionItem.id ? 'Save' : 'Add'}}</button>
                        <button class="btn btn-primary" ng-click="cancel()"><i class="fa fa-times"></i> Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        window.product = {!! json_encode($product) !!};
        window.categoriesTree = {!! json_encode($categoriesTree) !!};
        window.categorySelected = {!! json_encode($categorySelected) !!};
        window.linkUpload = '/api/product/file';
    </script>
    {!! Html::script('bower_components/ckeditor/ckeditor.js?v='.getVersionScript())!!}
    {!! Html::script('bower_components/ckeditor/adapters/jquery.js?v='.getVersionScript())!!}
    {!! Html::script('core/components/product/ProductService.js?v='.getVersionScript())!!}
    {!! Html::script('core/components/product/ProductController.js?v='.getVersionScript())!!}
    {!! Html::script('core/shared/select-category/SelectLevelCategory.js?v='.getVersionScript())!!}
    {!! Html::script('core/shared/file-upload/fileUploadDirective.js?v='.getVersionScript())!!}
    {!! Html::script('core/shared/file-upload/fileService.js?v='.getVersionScript())!!}
@endsection