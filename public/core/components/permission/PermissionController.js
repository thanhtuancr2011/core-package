permissionApp.controller('PermissionController', ['$scope', '$uibModal', '$filter', 'ngTableParams', 'PermissionService', '$timeout', function ($scope, $uibModal, $filter, ngTableParams, PermissionService, $timeout) {
    /* When js didn't  loaded then hide table user */
    $('.container-fluid').removeClass('hidden');
    $('#page-loading').css('display', 'none');

    /* Not show search in table user */
    $scope.isSearch = false;

    /* Set data user to scope */
    $scope.data = PermissionService.setPermissions(angular.copy(window.permissions));

    /* Use ng-table for table user */
    $scope.tableParams = new ngTableParams({
        page: 1,
        count: 10,
        filter: {
            name: ''
        },
        sorting: {
            name: 'asc'
        }

    }, {
        total: $scope.data.length,
        getData: function ($defer, params) {
            var orderedData = params.filter() ? $filter('filter')($scope.data, params.filter()) : $scope.data; /* Filter user */
            orderedData = params.sorting() ? $filter('orderBy')(orderedData, params.orderBy()) : orderedData; /* Sort user */
            $defer.resolve(orderedData.slice((params.page() - 1) * params.count(), params.page() * params.count())); /* Paging */
        }
    })

    $scope.getModalPermisson = function(id){
        var template = '/admin/permission/create?v='+ new Date().getTime();  /* Create permission */
        if(typeof id != 'undefined'){
            template = '/admin/permission/'+ id + '/edit?v=' + new Date().getTime(); /* Edit permission */
        }
        var modalInstance = $uibModal.open({
            animation: $scope.animationsEnabled,
            templateUrl: window.baseUrl + template,
            controller: 'ModalCreatePermissionCtrl',
            size: null,
            resolve: {
            }
            
        });

        /* After create or edit permission then reset permission and reload ng-table */
        modalInstance.result.then(function (data) {
            $scope.data = PermissionService.getPermissions();
            $scope.tableParams.reload();
        }, function () {

           });
    };

    /* Delete permission */
    $scope.removePermission = function(id, size){
        var template = '/core/components/permission/view/DeletePermission.html?v=' + new Date().getTime() /* Delete permission */
        var modalInstance = $uibModal.open({
            animation: $scope.animationsEnabled,
            templateUrl: window.baseUrl + template,
            controller: 'ModalDeletePermissionCtrl',
            size: size,
            resolve: {
                permissionId: function(){
                    return id;
                }
            }
        });

        /* After create or edit permission then reset permission and reload ng-table */
        modalInstance.result.then(function (data) {
            $scope.data = PermissionService.getPermissions();
            $scope.tableParams.reload();
        }, function () {

           });
    };

}])
.controller('ModalCreatePermissionCtrl', ['$scope', '$uibModalInstance', 'PermissionService', function ($scope, $uibModalInstance, PermissionService) {

    /* When user click add or edit permission */
    $scope.createPermission = function () {
        $scope.permissionItem.slug = $scope.permissionItem.name.replace(/\s+/g,'.').toLowerCase();
        $scope.permissionItem.display_name = $scope.permissionItem.name;
        $('#page-loading').css('display', 'block');
        PermissionService.createPermissionProvider($scope.permissionItem).then(function (data){
            if(data.status == 0){
                $('#page-loading').css('display', 'none');
                $scope.errors = data.error.slug[0];
            } else{
                $('#page-loading').css('display', 'none');
                $uibModalInstance.close(data);
            }
        })
    };

    /* When user click cancel then close modal popup */
    $scope.cancel = function () {
        $uibModalInstance.dismiss('cancel');
    };
}])
.controller('ModalDeletePermissionCtrl', ['$scope', '$uibModalInstance', 'permissionId', 'PermissionService', function ($scope, $uibModalInstance, permissionId, PermissionService) {
    /* When user click Delete permission */
    $scope.submit = function () {
        $('#page-loading').css('display', 'block');
        PermissionService.deletePermission(permissionId).then(function (){
            $('#page-loading').css('display', 'none');
            $uibModalInstance.close();
        });
    };

    /* When user click cancel then close modal popup */
    $scope.cancel = function () {
        $uibModalInstance.dismiss('cancel');
    };
}]);
