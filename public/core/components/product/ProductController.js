productApp.controller('ProductController', ['$scope', '$uibModal', '$filter', 'ngTableParams', 'ProductService', function ($scope, $uibModal, $filter, ngTableParams, ProductService) {
	/* When js didn't  loaded then hide table products */
	$('.container-fluid').removeClass('hidden');
	$('#page-loading').css('display', 'none');

	/* Not show search in table products */
	$scope.isSearch = false;

	/* Set data products to scope */
	$scope.data = ProductService.setProducts(angular.copy(window.products));

	/* Lists map id with name of category */
	$scope.listCategories = window.listsMapCategories;

	/* Use ng-table for table products */
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
        	var orderedData = params.filter() ? $filter('filter')($scope.data, params.filter()) : $scope.data;       /* Filter products */
        	orderedData = params.sorting() ? $filter('orderBy')(orderedData, params.orderBy()) : orderedData;        /* Sort products */
            $defer.resolve(orderedData.slice((params.page() - 1) * params.count(), params.page() * params.count())); /* Paging */
        }
    })

	$scope.createProduct = function(){
		var template = '/admin/product/create?v=' + new Date().getTime();  /* Create product */
		window.location.href = window.baseUrl + template;
	};

	/**
	 * Edit product
	 * @author Thanh Tuan <thanhtuancr2011@gmail.com>
	 * @param  {Int} id ProductId
	 * @return {Void}    
	 */
	$scope.editProduct = function(id) {
		var template = '/admin/product/'+ id + '/edit?v=' + new Date().getTime(); /* Edit product */
		window.location.href = window.baseUrl + template;
	}

	/* Delete product */
	$scope.removeProduct = function(id, size){
		var template = '/core/components/product/view/DeleteProduct.html?v=' + new Date().getTime();  /* Delete product */
		var modalInstance = $uibModal.open({
		    animation: $scope.animationsEnabled,
		    templateUrl: window.baseUrl + template,
		    controller: 'ModalDeleteProductCtrl',
		    size: size,
		    resolve: {
		    	productId: function(){
		            return id;
		        }
		    }
		    
		});

		/* After create or edit product then reset product and reload ng-table */
		modalInstance.result.then(function (data) {
			$scope.data = ProductService.getProducts();
			$scope.tableParams.reload();
		}, function () {

		   });
	};

}])
.controller('ModalCreateProductCtrl', ['$scope', 'ProductService', '$timeout', 'Upload', function ($scope, ProductService, $timeout, Upload) {
	/* Show categories tree */
	$timeout(function(){
		$('.container-fluid').removeClass('hidden');
		$('#page-loading').css('display', 'none');
		$scope.categoriesTree = angular.copy(window.categoriesTree);
		var editor = CKEDITOR.replace('description');
		$(".color-product").select2();
	});

	$scope.initCurrency = function(id) {
		$("#" + id).maskMoney({suffix: ' ₫', precision: 0});
    };

    $scope.initNumberAvailibility = function () {
    	$("#availibility").maskMoney({suffix: ' products', precision: 0});
    };

    $scope.initNumberWeight = function () {
    	$("#weight").maskMoney({suffix: ' gram', precision: 0});
    };

	$scope.requiredDescription = true;

	// Event on change ck editor
	CKEDITOR.on("instanceCreated", function(event) {
	    event.editor.on("change", function () {
	    	$timeout(function(){
	    		if (event.editor.getData() != '') {
		        	$scope.requiredDescription = false;
		        	$scope.productItem.description = event.editor.getData();
		        } else {
		        	$scope.requiredDescription = true;
		        	$scope.productItem.description = '';
		        }
	    	});
	    });
	});

	$scope.requiredCategory = false;

	$scope.replaceString = function (str) {  
		str= str.toLowerCase();  
		str= str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g,"a");  
		str= str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g,"e");  
		str= str.replace(/ì|í|ị|ỉ|ĩ/g,"i");  
		str= str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g,"o");  
		str= str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g,"u");  
		str= str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g,"y");  
		str= str.replace(/đ/g,"d");  
		return str;  
	}

	$scope.chooseCategory = function (categoryId) {
		if (angular.isDefined(categoryId)) {
			$scope.requiredCategory = false;
		}
	}

	/* When user click add or edit product */
	$scope.submit = function (validate, type) {

		$scope.type = type;
		
		$scope.submitted  = true;

		// Check choose category
		if (!$scope.productItem.category_id) {
			$scope.requiredCategory = true;
			return;
		}

		if ($scope.productItem.description != '') {
			$scope.requiredDescription = false;
		}

		if ($scope.requiredDescription) {
			return;
		}

		// Validate
  		if(validate){
			return;
  		}

		if($scope.requiredDescription) {
			return;
		}

		// Format price
		$scope.productItem.price =  parseInt($scope.productItem.price.replace(/,/gi, ""));

		// Format old_price
		if (angular.isDefined($scope.productItem.old_price) && $scope.productItem.old_price != null) {
			$scope.productItem.old_price = parseInt($scope.productItem.old_price.replace(/,/gi, ""));
		}

		var alias = $scope.replaceString($scope.productItem.name);

		$scope.productItem.alias = alias.replace(/\s+/g,'-').toLowerCase();

		$('#page-loading').css('display', 'block');

		ProductService.createProductProvider($scope.productItem).then(function (data){
			// If name has exists
			if(data.status == 0){
				$scope.nameExists = true;
				$scope.messageNameExists = data.errors.alias[0];
			} else{
				$scope.productItem.id = data.product.id;
				$scope.isSavedData = true;
			}
		});
	};

	// Check file upload
	$scope.$watch('fileUploaded', function(newVal, oldVal) {
		if (angular.isDefined(newVal)) {
            $scope.productItem.fileUploaded = newVal.files;
            if ($scope.isSavedData) {
                // When create product
                if ($scope.type == 'Add') {
                    ProductService.createImageProduct($scope.productItem).then(function (data){
                        if(data.status != 0){
                            window.location.href = window.baseUrl + '/admin/product';
                        }
                    });
                // When update product
                } else {
                    ProductService.updateImageProduct($scope.productItem).then(function (data){
                        if(data.status != 0){
                            window.location.href = window.baseUrl + '/admin/product';
                        }
                    });
                }
            }
        }
    });

    /* When data is saved but no file then redirect to root manager */
	$scope.onRedirect = function(redirect) {
		if ($scope.type != 'Add') {
            ProductService.updateImageProduct($scope.productItem).then(function (data){
                if(data.status != 0){
                    window.location.href = window.baseUrl + '/admin/product';
                }
            });
        }
        window.location.href = window.baseUrl + '/admin/product';
	}

	/* When user click cancel then close modal popup */
	$scope.cancel = function () {
		window.location.href = window.baseUrl + '/admin/product';
	};

}])
.controller('ModalDeleteProductCtrl', ['$scope', '$uibModalInstance', 'productId', 'ProductService', 
	function ($scope, $uibModalInstance, productId, ProductService) {
		/* When user click Delete product */
		$scope.submit = function () {
			$('#page-loading').css('display', 'block');
			ProductService.deleteProduct(productId).then(function (){
				$('#page-loading').css('display', 'none');
				$uibModalInstance.close();
			});
		};

		/* When user click cancel then close modal popup */
		$scope.cancel = function () {
		    $uibModalInstance.dismiss('cancel');
		};
	}
])
.directive('stringToNumber', function() {
  	return {
	    require: 'ngModel',
	    link: function(scope, element, attrs, ngModel) {
	      	ngModel.$parsers.push(function(value) {
	        	return '' + value;
	      	});
	      	ngModel.$formatters.push(function(value) {
	        	return parseFloat(value, 10);
	      	});
	    }
  	};
});
