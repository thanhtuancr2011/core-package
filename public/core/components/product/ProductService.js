var productApp = angular.module('product', []);
productApp.factory('ProductResource',['$resource', function ($resource){
    return $resource('/api/product/:method/:id', {'method':'@method','id':'@id'}, {
        add: {method: 'post'},
        save:{method: 'post'},
        update:{method: 'put'}
    });
}])
.service('ProductService', ['ProductResource', '$q', function (ProductResource, $q) {
    var that = this;
    var products = [];

    /* Function create new product */
	this.createProductProvider = function(data){
        /* If isset id of product then call function edit product */
        if(typeof data['id'] != 'undefined') {
            return that.editProductProvider(data);
        }
		var defer = $q.defer(); 
        var temp  = new ProductResource(data);
        /* Create product successfull */
        temp.$save({}, function success(data) {
            /* If product is created */
            if(data.status != 0) {
                /* Push product is created to array products */
                products.push(data.product);
            }
            /* Resolve result */
            defer.resolve(data);
        },
        /* If create product is error */
        function error(reponse) {
            /* Resolve result */
        	defer.resolve(reponse.data);
        });

        return defer.promise;  
	};

    /* Function edit product */
    this.editProductProvider = function(data){
        var defer = $q.defer(); 
        var temp  = new ProductResource(data);
        /* Update product successfull */
        temp.$update({id: data['id']}, function success(data) {
            /* If product is edited */
            if(data.status != 0){
                defer.resolve(data);
            }
        },
        /* If create product is error */
        function error(reponse) {
            defer.resolve(reponse.data);
        });
        
        return defer.promise;  
    };

    /* Function edit product */
    this.createImageProduct = function(data){
        var defer = $q.defer(); 
        var temp  = new ProductResource(data);
        /* Update product successfull */
        temp.$save({method: 'create-image-product'}, function success(data) {
            /* If product is edited */
            if(data.status != 0){
                defer.resolve(data);
            }
        },
        /* If create product is error */
        function error(reponse) {
            defer.resolve(reponse.data);
        });
        
        return defer.promise;  
    };

    /* Function edit product and update images of product */
    this.updateImageProduct = function(data){
        var defer = $q.defer(); 
        var temp  = new ProductResource(data);
        /* Update product successfull */
        temp.$save({method: 'update-image-product'}, function success(data) {
            /* If product is edited */
            if(data.status != 0){
                defer.resolve(data);
            }
        },
        /* If create product is error */
        function error(reponse) {
            defer.resolve(reponse.data);
        });
        
        return defer.promise;  
    };

    /* Function delete product */
    this.deleteProduct = function (id) {
        var defer = $q.defer(); 
        var temp  = new ProductResource();
        /* Delete product is successfull*/
        temp.$delete({id: id}, function success(data) {
            /* If product is deleted */
            if(data.status != 0){
                for (var i in products) {
                    if (products[i].id == id) {
                        products.splice(i, 1);
                    }
                }
            }
            defer.resolve(data);
        },
        
        /* If delete product is error */
        function error(reponse) {
            defer.resolve(reponse.data);
        });
        return defer.promise;  
    }

    /* Set product to array products */
    this.setProducts = function(data) {
        products = data;
        return products;
    }
    
    /* Get array products */
    this.getProducts = function() {
        return products;
    }

}]);
