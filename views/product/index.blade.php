@extends('core::master')
@section('title')
    Products
@endsection
@section('content')
    <!-- Page Content -->
    <div id="page-wrapper" data-ng-controller="ProductController">
        <div class="container-fluid hidden">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="page-header">Products manager</h3>
                    <h3 class="c-m">
                        <a data-toggle="modal" href="javascript:void(0)" ng-click="createProduct()" class="btn btn-primary pull-right">
                            <i class="fa fa-plus"></i> Add Product
                        </a>
                    </h3>
                </div>
                <table class="table fix-height-tb table-striped" ng-table="tableParams" show-filter="isSearch">
                    <a class="fixed-search" href="javascript:void(0)" ng-click="isSearch = !isSearch">
                        <i class="fa fa-search"></i>
                    </a>
                    <tbody>
                        <tr ng-repeat="product in $data">
                            <td class="text-center" data-title="'Name'" filter="{ 'name': 'text' }" sortable="'name'">
                                @{{product.name}}
                            </td>
                            <td class="text-center" data-title="'Category'" sortable="'parent_id'">
                                <span >@{{listCategories[product.category_id]}}</span>
                            </td>
                            <td class="text-center" data-title="'Keywords'" filter="{ 'keywords': 'text' }" sortable="'keywords'">
                                @{{product.keywords}}
                            </td>
                            <td class="text-center" data-title="''">
                                <a href="javascript:void(0)" ng-click="editProduct(product.id)" class="action-icon">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <a href="javascript:void(0)" ng-click="removeProduct(product.id, 'sm')" class="action-icon">
                                    <i class="fa fa-trash-o"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /#page-wrapper -->
@endsection

@section('script')
    <script>
        window.products = {!! json_encode($products) !!};
        window.listsMapCategories = {!! json_encode($listsMapCategories) !!};
    </script>
    {!! Html::script('core/components/product/ProductService.js?v='.getVersionScript())!!}
    {!! Html::script('core/components/product/ProductController.js?v='.getVersionScript())!!}
    {!! Html::script('core/shared/select-category/SelectLevelCategory.js?v='.getVersionScript())!!}
@endsection