var permissionApp = angular.module('permission', []);
permissionApp.factory('PermissionResource',['$resource', function ($resource){
    return $resource('/api/permission/:method/:id', {'method':'@method','id':'@id'}, {
        add: {method: 'post'},
        save:{method: 'post'},
        update:{method: 'put'}
    });
}])
.service('PermissionService', ['PermissionResource', '$q', function (PermissionResource, $q) {
    var that = this;
    var permissions = [];

    /* Function create new permission */
    this.createPermissionProvider = function(data){

        /* If isset id of permission then call function edit permission */
        if(typeof data['id'] != 'undefined') {
            return that.editPermissionProvider(data);
        }
        
        var defer = $q.defer(); 
        var temp  = new PermissionResource(data);

        temp.$save({}, function success(data) {
            /* If permission is created */
            if(data.status != 0) {
                permissions.push(data.permission);
            }
            /* Resolve result */
            defer.resolve(data);
        },
        
        /* If create permission is error */
        function error(reponse) {
            /* Resolve result */
            defer.resolve(reponse.data);
        });

        return defer.promise;  
    };

    /* Function edit permission */
    this.editPermissionProvider = function(data){
        var defer = $q.defer(); 
        var temp  = new PermissionResource(data);
        /* Update permission successfull */
        temp.$update({id: data['id']}, function success(data) {
            /* If permission is edited */
            if(data.status != 0){
                /* Each permission */
                for (var key in permissions) {
                    /* Set permission edited */
                    if (permissions[key].id == data.permission.id){
                        permissions[key] = data.permission;
                        break;
                    }
                }
            }
            defer.resolve(data);
        },
        /* If create permission is error */
        function error(reponse) {
            /* If create permission is error */
            defer.resolve(reponse.data);
        });
        
        return defer.promise;  
    };

    /* Function delete permission */
    this.deletePermission = function (id) {
        var defer = $q.defer(); 
        var temp  = new PermissionResource();
        /* Delete permission is successfull*/
        temp.$delete({id: id}, function success(data) {
            /* If permission is deleted */
            if(data.status != 0){
                /* Each permission */
                for (var key in permissions) {
                    /* Delete permission is deleted in array permissions */
                    if (permissions[key].id == id){
                        permissions.splice(key, 1);
                        break;
                    }
                }
            }
            defer.resolve(data);
        },
        
        /* If delete permission is error */
        function error(reponse) {
            defer.resolve(reponse.data);
        });
        return defer.promise;  
    }

    /* Set permission to array permissions */
    this.setPermissions = function(data) {
        permissions = data;
        return permissions;
    }
    
    /* Get array permissions */
    this.getPermissions = function() {
        return permissions;
    }

}]);
