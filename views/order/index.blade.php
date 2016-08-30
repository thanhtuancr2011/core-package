@extends('core::master')
@section('title')
    Order
@endsection
@section('content')
    <!-- Page Content -->
    <div id="page-wrapper" data-ng-controller="OrderController">
        <div class="container-fluid hidden">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="page-header">Orders Manager</h3>
                </div>
                <table class="table fix-height-tb table-striped" ng-table="tableParams" show-filter="isSearch">
                    <a class="fixed-search" href="javascript:void(0)" ng-click="isSearch = !isSearch">
                        <i class="fa fa-search"></i>
                    </a>
                    <tbody>
                        <tr ng-repeat="order in $data">
                            <td data-title="'Customer'" filter="{ 'order_id': 'text' }" sortable="'order_id'">
                                @{{order.id}}
                            </td>
                            <td data-title="'Email'" sortable="'quantity'">
                                <span >@{{order.quantity}}</span>
                            </td>
                            <td data-title="'Amount'" filter="{ 'amount': 'text' }" sortable="'amount'">
                                @{{order.amount | currency:"":0}} ₫
                            </td>
                            <td data-title="'Has send email'" filter="{ 'status': 'text' }" sortable="'status'">
                                @{{(order.status) ? 'Đã gửi mail' : 'Chưa gửi mail'}}
                            </td>
                            <td data-title="'Confirm'">
                                @{{order.name}}
                            </td>
                            <td data-title="'Confirm date'" filter="{ 'status': 'text' }" sortable="'status'">
                                @{{(order.status) ? 'Đã gửi mail' : 'Chưa gửi mail'}}
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
        window.listOrders = {!! json_encode($listOrders) !!};
    </script>
    {!! Html::script('core/components/order/OrderService.js?v='.getVersionScript())!!}
    {!! Html::script('core/components/order/OrderController.js?v='.getVersionScript())!!}
@endsection