@extends('core::master')
@section('title')
    Collections
@endsection
@section('content')
    <div id="page-wrapper" data-ng-controller="CollectionController">
        <div class="container-fluid hidden">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="page-header"> Collections Manager </h3>
                    <h3 class="c-m">
                        <a data-toggle="modal" href="javascript:void(0)" ng-click="getModalCollection()" class="btn btn-primary pull-right">
                            <i class="fa fa-plus"></i> Add Collection
                        </a>
                    </h3>
                </div>
                <table class="table table-hover fix-height-tb table-striped" ng-table="tableParams" show-filter="isSearch">
                    <a class="fixed-search" href="javascript:void(0)" ng-click="isSearch = !isSearch">
                        <i class="fa fa-search"></i>
                    </a>
                    <tbody>
                        <tr ng-repeat="collection in $data">
                            <td data-title="'Title'" filter="{ 'title': 'text' }" sortable="'title'" >
                                @{{collection.title}}
                            </td>
                            <td data-title="'Author'" filter="{ 'author': 'text' }" sortable="'author'" >
                                <img class="img-circle" ng-src="@{{collection.authAvatar}}" alt="" height="40">
                                @{{collection.author}}
                            </td>
                            <td class="text-center" data-title="'Description'" filter="{ 'description': 'text' }" sortable="'description'">
                                @{{collection.description}}
                            </td>
                            <td class="text-center" data-title="'Status'" sortable="'status'">
                                @{{collection.status}}
                            </td>
                            <td class="text-center" data-title="''">
                                <a ng-click="getModalCollection(collection.id)" class="action-icon">
                                    <i class="fa fa-pencil-square-o"></i>
                                </a>
                                <a ng-click="removeCollection(collection.id, 'sm')" class="action-icon">
                                    <i class="fa fa-trash-o"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table> 
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        window.collections = {!! json_encode($items) !!}
    </script>
    {!! Html::script('core/components/collection/CollectionService.js?v='.getVersionScript())!!}
    {!! Html::script('core/components/collection/CollectionController.js?v='.getVersionScript())!!}
@endsection