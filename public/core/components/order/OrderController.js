orderApp.controller('OrderController', ['$scope', '$uibModal', '$filter', 'ngTableParams', 'OrderService', function ($scope, $uibModal, $filter, ngTableParams, OrderService) {
	/* When js didn't  loaded then hide table listOrders */
	$('.container-fluid').removeClass('hidden');
	$('#page-loading').css('display', 'none');

	/* Not show search in table listOrders */
	$scope.isSearch = false;

	/* Set data listOrders to scope */
	$scope.data = OrderService.setOrders(angular.copy(window.listOrders));

	/* Use ng-table for table listOrders */
	$scope.tableParams = new ngTableParams({
        page: 1,
        count: 50,
        filter: {
            id: ''
        },
        sorting: {
            id: 'asc'
        }

    }, {
        total: $scope.data.length,
        getData: function ($defer, params) {
        	var orderedData = params.filter() ? $filter('filter')($scope.data, params.filter()) : $scope.data;       /* Filter products */
        	orderedData = params.sorting() ? $filter('orderBy')(orderedData, params.orderBy()) : orderedData;        /* Sort products */
            $defer.resolve(orderedData.slice((params.page() - 1) * params.count(), params.page() * params.count())); /* Paging */
        }
    });

}]);
