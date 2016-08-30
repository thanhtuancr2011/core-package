@extends('core::master')
@section('title')
    Permission
@endsection
@section('content')
    <div id="page-wrapper" data-ng-controller="PermissionController">
        <div class="container-fluid hidden">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="page-header">Permission Manager</h3>
                    <h3 class="c-m">
                        <a data-toggle="modal" href="javascript:void(0)" ng-click="getModalPermisson()" class="btn btn-primary pull-right">
                            <i class="fa fa-plus"></i> Add Permission
                        </a>
                    </h3>
                </div>
                <table class="table table-hover fix-height-tb table-striped" ng-table="tableParams" show-filter="isSearch">
                    <a class="fixed-search" href="javascript:void(0)" ng-click="isSearch = !isSearch">
                        <i class="fa fa-search"></i>
                    </a>
                    <tbody>
                        <tr ng-repeat="permission in $data">
                            <td data-title="'Name'" filter="{ 'name': 'text' }" sortable="'name'" >
                                @{{permission.name}}
                            </td>
                            <td class="text-center" data-title="'Description'" filter="{ 'description': 'text' }" sortable="'description'">
                                @{{permission.description}}
                            </td>
                            <td class="text-center" data-title="''">
                                <a ng-click="getModalPermisson(permission.id)" class="action-icon">
                                    <i class="fa fa-pencil-square-o"></i>
                                </a>
                                <a ng-click="removePermission(permission.id, 'sm')" class="action-icon">
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
        window.permissions = {!! json_encode($items) !!}
    </script>
    {!! Html::script('core/components/permission/PermissionService.js?v='.getVersionScript())!!}
    {!! Html::script('core/components/permission/PermissionController.js?v='.getVersionScript())!!}
@endsection