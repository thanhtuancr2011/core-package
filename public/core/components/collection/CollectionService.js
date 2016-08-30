var collectionApp = angular.module('collection', []);
collectionApp.factory('CollectionResource',['$resource', function ($resource){
    return $resource('/api/collection/:method/:id', {'method':'@method','id':'@id'}, {
        add: {method: 'post'},
        save:{method: 'post'},
        update:{method: 'put'}
    });
}])
.service('CollectionService', ['CollectionResource', '$q', function (CollectionResource, $q) {
    var that = this;
    var collections = [];

    /* Function create new collection */
    this.createCollectionProvider = function(data){

        /* If isset id of collection then call function edit collection */
        if(typeof data['id'] != 'undefined') {
            return that.editCollectionProvider(data);
        }
        
        var defer = $q.defer(); 
        var temp  = new CollectionResource(data);

        temp.$save({}, function success(data) {
            /* If collection is created */
            if(data.status != 0) {
                collections.push(data.collection);
            }
            /* Resolve result */
            defer.resolve(data);
        },
        
        /* If create collection is error */
        function error(reponse) {
            /* Resolve result */
            defer.resolve(reponse.data);
        });

        return defer.promise;  
    };

    /* Function edit collection */
    this.editCollectionProvider = function(data){
        var defer = $q.defer(); 
        var temp  = new CollectionResource(data);
        /* Update collection successfull */
        temp.$update({id: data['id']}, function success(data) {
            /* If collection is edited */
            if(data.status != 0){
                /* Each collection */
                for (var key in collections) {
                    /* Set collection edited */
                    if (collections[key].id == data.collection.id){
                        collections[key] = data.collection;
                        break;
                    }
                }
            }
            defer.resolve(data);
        },
        /* If create collection is error */
        function error(reponse) {
            /* If create collection is error */
            defer.resolve(reponse.data);
        });
        
        return defer.promise;  
    };

    /* Function delete collection */
    this.deleteCollection = function (id) {
        var defer = $q.defer(); 
        var temp  = new CollectionResource();
        /* Delete collection is successfull*/
        temp.$delete({id: id}, function success(data) {
            /* If collection is deleted */
            if(data.status != 0){
                /* Each collection */
                for (var key in collections) {
                    /* Delete collection is deleted in array collections */
                    if (collections[key].id == id){
                        collections.splice(key, 1);
                        break;
                    }
                }
            }
            defer.resolve(data);
        },
        
        /* If delete collection is error */
        function error(reponse) {
            defer.resolve(reponse.data);
        });
        return defer.promise;  
    }

    /* Function edit collection and create images */
    this.createImageCollection = function(data){
        var defer = $q.defer(); 
        var temp  = new CollectionResource(data);
        /* Update collection successfull */
        temp.$save({method: 'create-image-collection'}, function success(data) {
            /* If collection is edited */
            if(data.status != 0){
                defer.resolve(data);
            }
        },
        /* If create collection is error */
        function error(reponse) {
            defer.resolve(reponse.data);
        });
        
        return defer.promise;  
    };

    /* Function edit collection and update images of collection */
    this.updateImageCollection = function(data){
        var defer = $q.defer(); 
        var temp  = new CollectionResource(data);
        /* Update collection successfull */
        temp.$save({method: 'update-image-collection'}, function success(data) {
            /* If collection is edited */
            if(data.status != 0){
                defer.resolve(data);
            }
        },
        /* If create collection is error */
        function error(reponse) {
            defer.resolve(reponse.data);
        });
        
        return defer.promise;  
    };

    /* Set collection to array collections */
    this.setCollections = function(data) {
        collections = data;
        return collections;
    }
    
    /* Get array collections */
    this.getCollections = function() {
        return collections;
    }

}]);
