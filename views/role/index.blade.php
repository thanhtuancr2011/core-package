@extends('core::master')
@section('title')
    Role
@endsection
@section('content')
    <div id="page-wrapper" data-ng-controller="RoleController">
        <div class="container-fluid hidden">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="page-header">Roles Manager</h3>
                    <h3 class="c-m">
                        <a data-toggle="modal" href="javascript:void(0)" ng-click="getModalRole()" class="btn btn-primary pull-right">
                            <i class="fa fa-plus"></i> Add Role
                        </a>
                    </h3>
                </div>
                <table class="table table-hover fix-height-tb table-striped" ng-table="tableParams" show-filter="isSearch">
                    <a class="fixed-search" href="javascript:void(0)" ng-click="isSearch = !isSearch">
                        <i class="fa fa-search"></i>
                    </a>
                    <tbody>
                        <tr ng-repeat="role in $data">
                            <td data-title="'Name'" filter="{ 'name': 'text' }" sortable="'name'" >
                                @{{role.name}}
                            </td>
                            <td class="text-center" data-title="'Description'" filter="{ 'description': 'text' }" sortable="'description'">
                                @{{role.description}}
                            </td>
                            <td class="text-center" data-title="''">
                                <a ng-click="getModalRole(role.id)" class="action-icon">
                                    <i class="fa fa-pencil-square-o"></i>
                                </a>
                                <a ng-click="removeRole(role.id, 'sm')" class="action-icon">
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
        window.roles = {!! json_encode($items) !!}
    </script>
    {!! Html::script('core/components/role/RoleService.js?v='.getVersionScript())!!}
    {!! Html::script('core/components/role/RoleController.js?v='.getVersionScript())!!}
@endsection