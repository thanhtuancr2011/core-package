var module = angular.module('multiSelect', []);
module.directive('multiSelect', [function(){
	return {
		restrict: 'EA',
		scope: {
			items : '=',
			itemsAssigned : '=',
			placeholder:'@',
			onChange: '&'
		},
		replace: true,
		templateUrl: '/core/shared/multi-select/view.html?v=2',
		link: function($scope, $elem, $attr){
			$scope.assignPermissions = function(){
				if(angular.isDefined($scope.selectedItemFrom) && $scope.selectedItemFrom.length == 0) return;

				for(var key in $scope.selectedItemFrom){
					var value = $scope.selectedItemFrom[key];
					$scope.itemsAssigned[value] = $scope.items[value];
					delete $scope.items[value];
				}

				$scope.selectedItemFrom = [];
				$scope.onChange();
			}

			$scope.undoPermissions = function(){
				if(angular.isArray($scope.items)){
					$scope.items = {};
				}
				
				if(angular.isDefined($scope.selectedItemTo) && $scope.selectedItemTo.length == 0) return;

				for(var key in $scope.selectedItemTo){
					var value = $scope.selectedItemTo[key];
					$scope.items[value] = $scope.itemsAssigned[value];
					delete $scope.itemsAssigned[value];
				}

				$scope.selectedItemTo = [];
				$scope.onChange();
			}
		}
	};
}])