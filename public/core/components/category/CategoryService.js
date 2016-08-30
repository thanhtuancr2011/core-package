var categoryApp = angular.module('category', []);
categoryApp.factory('CategoryResource',['$resource', function ($resource){
    return $resource('/api/category/:method/:id', {'method':'@method','id':'@id'}, {
        add: {method: 'post'},
        save:{method: 'post'},
        update:{method: 'put'}
    });
}])
.service('CategoryService', ['CategoryResource', '$q', function (CategoryResource, $q) {
    var that = this;
    var categories = [];

    /* Function create new category */
	this.createCategoryProvider = function(data){
        /* If isset id of category then call function edit category */
        if(typeof data['id'] != 'undefined') {
            return that.editCategoryProvider(data);
        }
		var defer = $q.defer(); 
        var temp  = new CategoryResource(data);
        /* Create category successfull */
        temp.$save({}, function success(data) {
            /* If category is created */
            if(data.status != 0) {
                /* Push category is created to array categories */
                categories.push(data.category);
            }
            /* Resolve result */
            defer.resolve(data);
        },
        /* If create category is error */
        function error(reponse) {
            /* Resolve result */
        	defer.resolve(reponse.data);
        });

        return defer.promise;  
	};

    /* Function edit category */
    this.editCategoryProvider = function(data){
        var defer = $q.defer(); 
        var temp  = new CategoryResource(data);
        /* Update category successfull */
        temp.$update({id: data['id']}, function success(data) {
            /* If category is edited */
            if(data.status != 0){
                /* Each category */
                for (var key in categories) {
                    /* Set category edited */
                    if (categories[key].id == data.category.id){
                        categories[key] = data.category;
                        break;
                    }
                }
            }
            defer.resolve(data);
        },
        /* If create category is error */
        function error(reponse) {
            defer.resolve(reponse.data);
        });
        
        return defer.promise;  
    };

    /* Function edit category */
    this.createImageCategory = function(data){
        var defer = $q.defer(); 
        var temp  = new CategoryResource(data);
        /* Update category successfull */
        temp.$save({method: 'create-image-category'}, function success(data) {
            /* If category is edited */
            if(data.status != 0){
                defer.resolve(data);
            }
        },
        /* If create category is error */
        function error(reponse) {
            defer.resolve(reponse.data);
        });
        
        return defer.promise;  
    };

    /* Function edit category and update images of category */
    this.updateImageCategory = function(data){
        var defer = $q.defer(); 
        var temp  = new CategoryResource(data);
        /* Update category successfull */
        temp.$save({method: 'update-image-category'}, function success(data) {
            /* If category is edited */
            if(data.status != 0){
                defer.resolve(data);
            }
        },
        /* If create category is error */
        function error(reponse) {
            defer.resolve(reponse.data);
        });
        
        return defer.promise;  
    };

    /* Function delete category */
    this.deleteCategory = function (id) {
        var defer = $q.defer(); 
        var temp  = new CategoryResource();
        /* Delete category is successfull*/
        temp.$delete({id: id}, function success(data) {
            /* If category is deleted */
            if(data.status != 0){
                for (var i in categories) {
                    if (categories[i].id == id) {
                        categories.splice(i, 1);
                    }
                }
            }
            defer.resolve(data);
        },

        /* If delete category is error */
        function error(reponse) {
            defer.resolve(reponse.data);
        });
        return defer.promise;  
    }

    /* Set category to array categories */
    this.setCategories = function(data) {
        categories = data;
        return categories;
    }
    
    /* Get array categories */
    this.getCategories = function() {
        return categories;
    }

}]);
