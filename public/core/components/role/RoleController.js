roleApp.controller('RoleController', ['$scope', '$uibModal', '$filter', 'ngTableParams', 'RoleService', '$timeout', function ($scope, $uibModal, $filter, ngTableParams, RoleService, $timeout) {
    /* When js didn't  loaded then hide table user */
    $('.container-fluid').removeClass('hidden');
    $('#page-loading').css('display', 'none');

    /* Not show search in table user */
    $scope.isSearch = false;

    /* Set data user to scope */
    $scope.data = RoleService.setRoles(angular.copy(window.roles));

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

    $scope.getModalRole = function(id){
        var template = '/admin/role/create?v='+ new Date().getTime();  /* Create role */
        if(typeof id != 'undefined'){
            template = '/admin/role/'+ id + '/edit?v=' + new Date().getTime(); /* Edit role */
        }
        var modalInstance = $uibModal.open({
            animation: $scope.animationsEnabled,
            templateUrl: window.baseUrl + template,
            controller: 'ModalCreateRoleCtrl',
            size: null,
            resolve: {
            }
            
        });

        /* After create or edit user then reset user and reload ng-table */
        modalInstance.result.then(function (data) {
            $scope.data = RoleService.getRoles();
            $scope.tableParams.reload();
        }, function () {

           });
    };

    /* Delete user */
    $scope.removeRole = function(id, size){
        var template = '/core/components/role/view/DeleteRole.html?v=' + new Date().getTime() /* Delete user */
        var modalInstance = $uibModal.open({
            animation: $scope.animationsEnabled,
            templateUrl: window.baseUrl + template,
            controller: 'ModalDeleteRoleCtrl',
            size: size,
            resolve: {
                roleId: function(){
                    return id;
                }
            }
        });

        /* After create or edit user then reset user and reload ng-table */
        modalInstance.result.then(function (data) {
            $scope.data = RoleService.getRoles();
            $scope.tableParams.reload();
        }, function () {

           });
    };

}])
.controller('ModalCreateRoleCtrl', ['$scope', '$uibModalInstance', 'RoleService', function ($scope, $uibModalInstance, RoleService) {

    /* When user click add or edit role */
    $scope.createRole = function () {
        $scope.roleItem.slug = $scope.roleItem.name.replace(/\s+/g,'.').toLowerCase();
        $scope.roleItem.display_name = $scope.roleItem.name;
        $('#page-loading').css('display', 'block');
        RoleService.createRoleProvider($scope.roleItem).then(function (data){
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
.controller('ModalDeleteRoleCtrl', ['$scope', '$uibModalInstance', 'roleId', 'RoleService', function ($scope, $uibModalInstance, roleId, RoleService) {
    /* When user click Delete user */
    $scope.submit = function () {
        $('#page-loading').css('display', 'block');
        RoleService.deleteRole(roleId).then(function (){
            $('#page-loading').css('display', 'none');
            $uibModalInstance.close();
        });
    };

    /* When user click cancel then close modal popup */
    $scope.cancel = function () {
        $uibModalInstance.dismiss('cancel');
    };
}]);
