userApp.controller('UserController', ['$scope', '$uibModal', '$filter', 'ngTableParams', 'UserService', '$timeout', function ($scope, $uibModal, $filter, ngTableParams, UserService, $timeout) {
	/* When js didn't  loaded then hide table user */
	$('.container-fluid').removeClass('hidden');
	$('#page-loading').css('display', 'none');

	/* Not show search in table user */
	$scope.isSearch = false;

	/* Set data user to scope */
	$scope.data = UserService.setUsers(angular.copy(window.users));

	if (angular.isDefined($scope.data)) {
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
	    });
	}

	/* Set role and permission available and assigned */
	if (angular.isDefined(window.dataController)) {
		$scope.id = window.dataController.id;
		$scope.listRoles = window.dataController.listRoles;
		$scope.userRoles = window.dataController.userRoles;
		$scope.listPermissions = window.dataController.listPermissions;
		$scope.userPermissions = window.dataController.userPermissions;
	}

	var initMessageError = function () {
        $scope.error = false;
        $scope.message = {};
        $scope.message.per = '';
        $scope.message.role = '';
        $scope.message.group = '';
    }

    $scope.updatePermission = function(){
        initMessageError();
        UserService.updatePermissions($scope.id, Object.keys($scope.userPermissions)).then(function(data){
            if(!data.status) {
                $scope.error = true;
                $scope.message.per = data.message;
            }
        });
    };

    $scope.updateRole = function(){        
        initMessageError();
        UserService.updateRoles($scope.id, Object.keys($scope.userRoles)).then(function(data){
            if(!data.status) {
                $scope.error = true;
                $scope.message.role = data.message;
            }
        });
    };

	$scope.getModalUser = function(id){
		var template = '/admin/user/create?v='+ new Date().getTime();  /* Create user */
		if(typeof id != 'undefined'){
			template = '/admin/user/'+ id + '/edit?v=' + new Date().getTime(); /* Edit user */
		}
		var modalInstance = $uibModal.open({
		    animation: $scope.animationsEnabled,
		    templateUrl: window.baseUrl + template,
		    controller: 'ModalCreateUserCtrl',
		    size: null,
		    resolve: {
		    }
		    
		});

		/* After create or edit user then reset user and reload ng-table */
		modalInstance.result.then(function (data) {
			$scope.data = UserService.getUsers();
			$scope.tableParams.reload();
		}, function () {

		   });
	};

	/* Delete user */
	$scope.removeUser = function(id, size){
		var template = '/core/components/user/view/DeleteUser.html?v=' + new Date().getTime() /* Delete user */
		var modalInstance = $uibModal.open({
		    animation: $scope.animationsEnabled,
		    templateUrl: window.baseUrl + template,
		    controller: 'ModalDeleteUserCtrl',
		    size: size,
		    resolve: {
		    	userId: function(){
		            return id;
		        }
		    }
		    
		});

		/* After create or edit user then reset user and reload ng-table */
		modalInstance.result.then(function (data) {
			$scope.data = UserService.getUsers();
			$scope.tableParams.reload();
		}, function () {

		   });
	};

}])
.controller('ModalCreateUserCtrl', ['$scope', '$uibModalInstance', 'UserService', function ($scope, $uibModalInstance, UserService) {
	/* Format phone number */
	$scope.formatPhoneNumber = function() {
        angular.element('#phone_number').mask('(999) 999-9999? x 99999',{ autoclear: false});
    };

    /* Validate phone number */
    $scope.validatePhone = function(number){
        $scope.formAddUser.phone_number.$invalid = false;
        if(typeof number != 'undefined') {
            number = number.replace(/[^0-9]/g,'');
            if(number.length != 0 && number.length < 10) {
                $scope.formAddUser.phone_number.$invalid = true;
            }
        }
    };

	/* When user click add or edit user */
	$scope.createUser = function () {
		$('#page-loading').css('display', 'block');
		UserService.createUserProvider($scope.userItem).then(function (data){
			if(data.status == 0){
				$('#page-loading').css('display', 'none');
				$scope.errors = data.error.email[0];
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
.controller('ModalDeleteUserCtrl', ['$scope', '$uibModalInstance', 'userId', 'UserService', function ($scope, $uibModalInstance, userId, UserService) {
	/* When user click Delete user */
	$scope.submit = function () {
		$('#page-loading').css('display', 'block');
		UserService.deleteUser(userId).then(function (){
			$('#page-loading').css('display', 'none');
			$uibModalInstance.close();
		});
	};

	/* When user click cancel then close modal popup */
	$scope.cancel = function () {
	    $uibModalInstance.dismiss('cancel');
	};
}]);
